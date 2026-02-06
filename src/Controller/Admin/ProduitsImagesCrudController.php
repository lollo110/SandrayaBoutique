<?php

namespace App\Controller\Admin;

use App\Entity\ProduitsImages;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProduitsImagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProduitsImages::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('produit'),
            ImageField::new('image')
                ->setBasePath('/assets/uploads')
                ->onlyOnIndex(),
            
            TextField::new('image')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->setFormTypeOptions([
                    'mapped' => false,
                    'required' => $pageName === 'new',
                ])
                ->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        $this->handleImageUpload($entityInstance);
        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        $this->handleImageUpload($entityInstance);
        parent::updateEntity($em, $entityInstance);
    }

    private function handleImageUpload($entityInstance)
    {
        /** @var UploadedFile $file */
        $file = $this->getContext()->getRequest()->files->get('ProduitsImages')['image'] ?? null;

        if($file){
            $newFilename = uniqid().'.'.$file->guessExtension();
            $file->move('assets/uploads', $newFilename);
            $entityInstance->setImage($newFilename);
        }
    }

    
public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
{
    $filesystem = new Filesystem();

    if ($entityInstance->getImage()) {
        $filePath = 'assets/uploads/' . $entityInstance->getImage();

        if ($filesystem->exists($filePath)) {
            $filesystem->remove($filePath);
        }
    }

    parent::deleteEntity($em, $entityInstance);
}
}
