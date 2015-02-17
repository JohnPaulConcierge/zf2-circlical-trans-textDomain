# zf2-circlical-trans-textDomain

Magic glue to connect ZfcTwig, the ZF2 Translator, and Twig's {% trans %}.  Usage is very simple; with this package included, modify your app config to add the "trans" extension this codebase provides.


### Requirements


|Item              |  Version     |
|------------------|--------------|
|PHP               | 5.5+         |
|Zend Framework    | 2.3.*        |


### Installation

Add this line to composer, and update:

```js
"saeven/zf2-circlical-trans": "dev-master",
```

### Configuration

In your module's application.config.php, make sure you've got these modules loaded:

    'ZfcTwig',
    'CirclicalTwigTrans'

It's assumed that you are managing locale and textDomain in your app's bootstrap.  In your Application module's ::onBootstrap() you are setting locale and the translator object for the view:

```php
public function onBootstrap(MvcEvent $e)
{
    $translator = $e->getApplication()->getServiceManager()->get('translator');
    $translator
        ->setLocale( 'fr_CA' )
        ->setFallbackLocale( 'en_US' );

    //for the view in application       
    $viewRenderer = $events->getApplication()->getServiceManager()->get('ViewRenderer');
    $plugIn = $viewRenderer->plugin('translate');
    $plugIn->setTranslator($translator, __NAMESPACE__);    
}
```

In any other Module.php you just set the TextDomain for the view:

```php

public function onBootstrap(MvcEvent $e)
{
    $viewRenderer = $events->getApplication()->getServiceManager()->get('ViewRenderer');
    $plugIn = $viewRenderer->plugin('translate');
    $plugIn->setTranslatorTextDomain(__NAMESPACE__); 
}
```

### Usage

Use 

```twig
{% trans "This is a sentence" %} 
```
to translate that string.

You can also do pluralization with:

```
{% set name = "Morpheus" %}
{% trans %}
   Hi {{ name }}, I took one blue pill.
{% plural pill_count %}
   Hi {{ name }}, I took {{ pill_count }} blue pills.
{% endtrans %}
```

You can test it with the ZF2 Skeleton, by translating "Home" to "fr_CA" which becomes "Acceuil" (good test).
