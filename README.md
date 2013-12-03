BtnPageBundle
=============

### Step 1: Add PageBundle in your composer.json (private repo)

```js
{
    "require": {
        "bitnoise/page-bundle": "dev-master",
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@github.com:Bitnoise/BtnPageBundle.git"
        }
    ],
}
```

### Step 2: Enable the bundle

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Btn\PageBundle\BtnPageBundle(),
        new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
    );
}
```

### Step 3: Import PageBundle routing

``` yaml
# app/config/routing.yml
btn_page:
    resource: "@BtnPageBundle/Controller/"
    type:     annotation
    prefix:   /
```

### Step 4: Update your database schema

``` bash
$ php app/console doctrine:schema:update --force
```

### Step 5: Setup parameters 

[example parameters](https://github.com/Bitnoise/BtnPageBundle/blob/master/docs/parameters.yml.example.md)
