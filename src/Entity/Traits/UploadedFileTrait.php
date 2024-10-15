<?php

namespace Lyssal\DoctrineExtraBundle\Entity\Traits;

use Lyssal\Exception\IoException;
use Lyssal\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * This trait helps to manage an upload file in an entity.
 * You have to create in your entity a property name $uploadedFile.
 */
trait UploadedFileTrait
{
    /**
     * New filename of the uploaded name.
     */
    protected string $uploadedFileFilename;

    /**
     * If the file has been uploaded (generally saved in the server).
     */
    protected bool $fileUploadIsSuccess = false;

    public function setUploadedFile(?UploadedFile $uploadedFile = null): void
    {
        $this->uploadedFile = $uploadedFile;

        if (null !== $this->uploadedFile && $this->uploadedFileIsValid()) {
            $this->uploadFile();
        }
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * Get the filename of the saved file.
     */
    public function getUploadedFileFilename(): string
    {
        return $this->uploadedFileFilename;
    }

    /**
     * Get the pathame of the saved file.
     */
    public function getUploadedFilePathname(): string
    {
        return $this->getUploadedFileDirectory().\DIRECTORY_SEPARATOR.$this->uploadedFileFilename;
    }

    /**
     * Return if the file has been uploaded with success.
     */
    public function fileUploadIsSuccess(): bool
    {
        return $this->fileUploadIsSuccess;
    }

    /**
     * Return if the uploaded file is valid.
     */
    public function uploadedFileIsValid(): bool
    {
        return null !== $this->uploadedFile;
    }

    /**
     * Upload the file.
     *
     * @param string|null $filename The new filename, else the upload filename will be used
     *
     * @throws IoException If the file can not be saved
     */
    public function uploadFile(?string $filename = null): void
    {
        if (!$this->uploadedFileIsValid()) {
            return;
        }

        $this->saveUploadedFile($filename, false);
    }

    /**
     * Save the uploaded file in the server.
     *
     * @param string|null $filename The new filename, else the upload filename will be used
     * @param bool        $replace  If an existing file has to be replaced, else the file will be renamed
     *
     * @return string The filename of the saved file
     *
     * @throws IoException If the file can not be saved
     */
    protected function saveUploadedFile(?string $filename = null, bool $replace = false): string
    {
        if (\UPLOAD_ERR_OK !== $this->getUploadedFile()->getError()) {
            throw new IoException($this->getUploadedFile()->getErrorMessage());
        }

        if (null === $filename) {
            $filename = $this->getUploadedFile()->getClientOriginalName();
        }

        $file = new File($this->getUploadedFile()->getRealPath());

        if ($file->move($this->getUploadedFileDirectory().\DIRECTORY_SEPARATOR.$filename, $replace)) {
            $this->setUploadedFile(null);
            $this->fileUploadIsSuccess = true;
            $this->uploadedFileFilename = $file->getFilename();

            return $this->uploadedFileFilename;
        }

        throw new IoException('The upload file can not be found.');
    }

    /**
     * The directory where the file will be saved.
     */
    abstract public function getUploadedFileDirectory(): string;
}
