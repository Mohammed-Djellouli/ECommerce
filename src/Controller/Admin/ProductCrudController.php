<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            // ...
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $required = true;
        if($pageName === 'edit'){
            $required = false;
        }
        return [
            TextField::new('name','Nom du produit')->setHelp('Nom de votre produit'),
            SlugField::new('slug','Slug')->setTargetFieldName('name')->setHelp('l\'URL généré automatiquement vers votre Produit'),
            TextEditorField::new('description','Description')->setHelp('Description du produit'),
            ImageField::new('illustration', 'Image')
                ->setUploadDir('public/uploads')
                ->setBasePath('/uploads')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setHelp('Image du produit en 600x600px')
                ->setRequired($required)
            ,
            NumberField::new('price','Prix')->setHelp('Prix H.T du produit'),
            ChoiceField::new('tva','TVA')->setChoices([
                '5,5%' => '5.5',
                '10%' => '10',
                '20%' => '20',
            ]),
            AssociationField::new('category','Categorie associée')->setHelp('Categorie de votre produit'),

        ];
    }

}
