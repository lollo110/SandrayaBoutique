<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    
    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),

        EmailField::new('email')->hideOnForm(),

        TextField::new('nom')->hideOnForm(),
        TextField::new('prenom')->hideOnForm(),
        TextField::new('username'),

        TextField::new('portable')->hideOnForm(),
        TextField::new('add_livraison', 'Adresse de livraison')->hideOnForm(),
        TextField::new('ville')->hideOnForm(),
        TextField::new('code_postal')->hideOnForm(),

        ArrayField::new('roles'),
    ];
}
    
}
