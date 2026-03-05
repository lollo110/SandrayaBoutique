<?php

namespace App\Controller\Admin;

use App\Entity\Commandes;
use App\Enum\Statut;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CommandesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commandes::class;
    }

     public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')
                ->hideOnForm(),

            DateTimeField::new('date_commande', 'Date commande')
                ->hideOnForm(),

            ChoiceField::new('statut')
                ->setChoices([
                    'En attente' => Statut::ENATTENTE,
                    'Payée' => Statut::PAYEE,
                    'Expédiée' => Statut::EXPEDIEE,
                    'Annulée' => Statut::ANNULEE,
                ])
                ->renderAsBadges([
                    Statut::ENATTENTE->value => 'warning',
                    Statut::PAYEE->value => 'success',
                    Statut::EXPEDIEE->value => 'info',
                    Statut::ANNULEE->value => 'danger',
                ]),

            TextField::new('add_livraison', 'Adresse livraison'),

            AssociationField::new('detailsCommandes')
                ->onlyOnDetail(),

            AssociationField::new('paiements')
                ->onlyOnDetail(),

        ];
    }
}
