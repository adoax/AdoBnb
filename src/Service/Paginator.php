<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * Classe de pagination qui extrait toute notion de calcul et de récupération de données de nos controllers
 * 
 * Elle nécessite après instanciation qu'on lui passe l'entité sur laquelle on souhaite travailler
 */
class Paginator
{
    /**
     * Le nom de l'entité sur laquelle  on veut effectuer une pagination
     *
     * @var string
     */
    private $entityClass;

    /**
     * Le nombre d'enregistrement à afficher par page
     *
     * @var integer
     */
    private $limit = 10;

    /**
     * La page sur laquelle on se trouve actuellement
     *
     * @var integer
     */
    private $currentPage = 1;

    /**
     * Le nom de la route que l'on veut utiliser pour les buttons de la naviguation
     *
     * @var string
     */
    private $route;

    /**
     * Le manager de doctrine qui permet de trouver le repo dont on à besoin
     *
     * @var ObjectManager
     */
    private $manager;

    /**
     * Le moteur de rendu Twig, qui permet de generer la pagination
     *
     * @var Twig\Environment
     */
    private $twig;

    /**
     * Le chemin vers le template qui contient la pagination
     *
     * @var string
     */
    private $templatePath;

    /**
     * Constructeur du service de pagination qui sera appelé par Symfony
     * 
     * IMPORTANT: configurer votre fichier services.yaml afin que Symfony sache quelle valeur utiliser pour $templatePath
     *
     * @param ObjectManager $manager
     * @param Environment $twig
     * @param RequestStack $request
     * @param string $templatePath
     */
    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath)
    {
        //Recuperer la route à utiliser à partir des attributs de la requette actuelle
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    /**
     * Permet d'afficer le rendu de la naviguation au sein d'un templte Twig
     * 
     * currentPage => La page actuelle sur laquelle on ce trouve
     * totalPage => le nombre total de page qui existent
     * route => le nom de la route à utiliser pour les liens de naviguation
     * 
     * Attention: cette fonction ne retourne rien, elle affiche directement le rendu
     *
     * @return void
     */
    public function display()
    {
        $this->twig->display($this->templatePath, [
            'currentPage' => $this->currentPage,
            'totalPages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    /**
     * Permet de récupérer les donnés paginées pour un entité spécifique
     * 
     * Recupere le repository de l'entité spécifiée, puis grace à la fonction findBy() on récupère
     * les données dans une certaine limite et en partant d'un offset
     * 
     * @throws Exception si la propriété &entityClass n'est pas définie
     *
     * @return Array
     */
    public function getData()
    {
        if (empty($this->entityClass)) {
            throw new Exception("Vous avez pas spécifée l'entité sur laquelle nous devons paginer ! Utiliser ma méthode setEntityClass() de votre objet Paginator");
        }
        //Calcul de l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        //Demande au repo de trouver les éléments à partir du offset et dans la limite imposée
        return $this->manager
            ->getRepository($this->entityClass)
            ->findBy([], ['id' => 'DESC'], $this->limit, $offset);
    }

    /**
     * Permet de recupérer le nombre de pages qui existent sur une entité particulière
     * 
     * Recupere le repository de l'entité spécifiée, puis recupere le nombre d'enregistrement total grâce à findAll()
     * 
     * @throws Exception si la propriété $entityClass n'est pas configurée
     *
     * @return int
     */
    public function getPages()
    {
        if (empty($this->entityClass)) {
            throw new Exception("Vous avez pas spécifée l'entité sur laquelle nous devons paginer ! Utiliser ma ùéthode setEntityClass() de votre objet Paginator");
        }
        //Calcul total des enregistrement de la table
        $total = count($this->manager
            ->getRepository($this->entityClass)
            ->findAll());

        //Nombre de page
        return ceil($total / $this->limit);
    }

    /**
     * Get the value of entityClass
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Set the value of entityClass
     *
     * @return  self
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Get the value of limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the value of limit
     *
     * @return  self
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the value of currentPage
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the value of currentPage
     *
     * @return  self
     */
    public function setCurrentPage($page)
    {
        $this->currentPage = $page;

        return $this;
    }

    /**
     * Get the value of route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set the value of route
     *
     * @return  self
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get the value of templatePath
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * Set the value of templatePath
     *
     * @return  self
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;

        return $this;
    }
}
