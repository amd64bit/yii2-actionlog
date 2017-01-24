Yii2 Action Log Extension
===================
An action log extension for yii2

# Installation

### 1.Download

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require atans/yii2-actionlog
```

or add

```
"atans/yii2-actionlog": "*"
```

to the require section of your `composer.json` file.


### 2.Update database schema

```
$ php yii migrate/up --migrationPath=@vendor/atans/yii2-actionlog/migrations
```


# Usage

```php
<?php
use atans\actionlog\models\ActionLog;

ActionLog::error('Some error message');
ActionLog::info('Some info message');
ActionLog::warning('Some warning message');
ActionLog::success('Some success message');


$data = ['success' => true, 'message' => 'Successfully']; // optional
$category = 'application'; // optional
ActionLog::success('Some success message', $data, $category);

```

# Management

Visit http://path/to/yii/?r=actionlog/admin using logged in account which role is admin 

You can change the role at `@vendor/atans/yii2-actionlog/Module.php`


