# Uploaded file

The UploadedFileTrait help you to manage an uploaded file / image in your entity.

## How to use

Here an example:

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lyssal\EntityBundle\Traits\UploadedFileTrait;
use Lyssal\File\File;
use Lyssal\File\Image;

#[ORM\Entity]
class MyEntity
{
    // This trait will manage your uploaded file
    use UploadedFileTrait;

    public const int IMAGE_MAX_WIDTH = 800;

    public const int IMAGE_MAX_HEIGHT = 600;

    #[ORM\Column(length: 255)]
    private string $imageFilename;

    // You must define the $uploadedFile property (without attribute)

    #[Assert\Image(detectCorrupted: true)]
    protected ?UploadedFile $uploadedFile = null;

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getImagePath(): string
    {
        return $this->getUploadedFileDirectory().'/'.$this->imageFilename;
    }
    
    // Define the `getUploadedFileDirectory()` method which returns the directory path where uploaded files will be saved

    public function getUploadedFileDirectory()
    {
        return 'images'.DIRECTORY_SEPARATOR.'entity_dir';
    }

    // Overload the uploadFile() method with your logic

    /**
     * Save the uploaded image.
     */
    public function uploadFile(?string $filename = null): void
    {
        // You can overload this method if you want filter some file extensions for example
        if (!$this->uploadedFileIsValid()) {
            return;
        }

        // Delete the old file if existing
        $this->deleteFile();
        // Save the file in the server
        $this->saveUploadedFile();

        // Here our image in the server
        $image = new Image($this->getUploadedFilePathname());
        // We minify the image filename to remove special characters,
        // and specify a maxlength (to be sure the varchar will be completely saved in database)
        $image->minify(null, null, true, 255);
        // We get the new filename
        $this->imageFilename = $image->getFilename();

        // We verify that the image format is managed
        if ($image->formatIsManaged()) {
            // We proportionally reduce the image
            $image->resizeProportionallyByMaxSize(self::IMAGE_MAX_WIDTH, self::IMAGE_MAX_HEIGHT);
        }
    }

    /**
     * Delete the image.
     */
    protected function deleteFile(): void
    {
        if (null !== $this->imageFilename) {
            $file = new File($this->getImagePath());
            $file->delete();
        }
    }
}
```

```yaml
{%- if my_entity.hasImage -%}
    <a href="{{ entity_path(my_entity) }}"><img src="{{ asset(my_entity.imagePath) }}" alt="{{ appellation(my_entity) }}"></a>
{%- endif -%}
```

Note: The file is uploaded when the `setUploadedFile()` is called so even if your submitted form is invalid, the file will be saved in the server and the Symfony asserts will not work.
You can use Doctrine events but if you upload only your file without modify an other field, the event will not be called.

If you want to use an event, change like this in your entity:

```php
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class MyEntity
{
    // ...
    
    // Overload the setUploadedFile() method in order to not call here the uploadFile() method
    // And so the Assert annotations in your $uploadedFile will work

    public function setUploadedFile(?UploadedFile $uploadedFile = null): void
    {
        $this->uploadedFile = $uploadedFile;
     
        return $this;
    }

    // Add the Doctrine events

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function uploadFile(?string $filename = null): void
    {
        // ...
    }
}
```

A trick could be to change an `updatedAt` property directly in the `setUploadedFile()` method.

```php
use Lyssal\DoctrineExtraBundle\Entity\Traits\UpdatedAtTrait;
use Lyssal\DoctrineExtraBundle\Entity\Traits\UploadedFileTrait;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class MyEntity
{
    use UpdatedAtTrait;
    use UploadedFileTrait;

    public function setUploadedFile(?UploadedFile $uploadedFile = null): void
    {
        $this->uploadedFile = $uploadedFile;
        // Force the Doctrine events
        $this->initUpdatedAt();
     
        return $this;
    }
    
    // ...
}
```
