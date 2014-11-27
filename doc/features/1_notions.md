# Notions #

## Layouts ##

Le `layout` décrit l'architecture et la structure globale de la page HTML. Il sera commun à un ensemble de pages sur le site.

Exemple : layout des pages articles 

## Zones ##

Une `Zone` définit un endroit dans un layout. Il peut y avoir plusieurs zones dans un layout. 

Lorsqu'on identifie un layout depuis le backoffice, l'application va automatiquement détecter les zones associés à celui-ci. Pour cela, il faut le définir de la manière suivante dans le layout :

    <?php $this->getZone('article_content'); ?>
    

Exemple : Zone de sidebar sur les pages articles     


## Blocks ##

Un `Block` permet de définir une règle métier spécifique pour une page donnée. Il est donc associé à une zone.

Ce block sera donc configurable depuis le backoffice, ce qui permettra de rendre dynamique en fonction des paramètres son contenu.

Exemple : Liste d'article configurable en fonction d'une categorie


## Templates ##

Le `template` définira le rendu d'un block. Un block pourra donc une vue différents en fonction du devise. On a actuellement deux environnement de template :

  1. Web
  2. Mobile

A l'heure actuelle, seul le template 'Web' est actif.

Exemple : Template du block de la liste d'article configurable en fonction d'une catégorie

## Association d'un bloc à un zone pour un layout ##

Un `block` devra donc être associé à un zone via le layout de la page concerné. Ainsi, en fonction des layouts, nous aurons des pages totalement différents en terme d'architecture mais aussi en terme de contenus.

Exemple : Association du block de la liste d'article en fonction d'une catégorie dans la zone sidebar sur le layout ds pages articles

##Entité##

Une `entité` définira une page sur le CMF, il aura alors une URL propre et spécifique qui sera générée selon les besoins du développeur.

On associera alors un layout à notre entité. Ainsi pour une URL donnée, on affichera le layout qui affichera les zones et donc les blocks associés à celles-ci.

Exemple : Entité Article : mon-premier-article-1.html

##Page##

Une `page` est une entité, cependant elle ne possède pas de contenu propre, au contraire d'un article, par exemple. 

Exemple : Une entité article affichera des blocs et son article qui sera rentré dans le back-office. Alors qu'une entité Page affichera uniquement une liste de blocs.


##Ressource##

Une `ressource` est associée à une entité. La ressource portera l'url de celle-ci et sa locale. Elle déterminera aussi comment l'entité devra être rendu, via le layout.

##Menu##

Le `menu` permet de définir un ensemble de lien, qu'il soit interne au CMF, ou externe.

##Feed##

Chaque action dans le Backoffice sera tracée, afin de savoir qui a fait quoi et quand. L'ensemble de ces actions seront alors listées dans les `feeds`.

##Révision##

On pourra `versionner` l'ensemble des objets du CMF. Ainsi nous allons pouvoir revenir en arrière sur le contenu d'un objet.


