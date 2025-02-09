<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            // ...
            ;
    }
    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('show')->setLabel('Détails')->linkToCrudAction('show');
        return $actions
            ->remove(Crud::PAGE_INDEX,Action::NEW)
            ->remove(Crud::PAGE_INDEX,Action::EDIT)
            ->remove(Crud::PAGE_INDEX,Action::DELETE)
            ->add(Crud::PAGE_INDEX,$show)
            ;
    }


public function show(AdminContext $context){
        $order = $context->getEntity()->getInstance();
        return $this->render('admin/order.html.twig', [
            'order' => $order,
        ]);
}


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt', 'Date'),
            NumberField::new('state','Statut')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user','Utilisateur'),
            TextField::new('carrierName','Transporteur'),
            NumberField::new('totalTva','Totale TVA'),
            NumberField::new('totalWt','Totale TTC'),
        ];
    }

}
