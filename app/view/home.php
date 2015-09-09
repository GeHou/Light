<html>
    <head>
        Home page!
    </head>
    <body>
        This is home page;<br />
        <? View::make('layout', get_defined_vars()); ?>
        <?= $title; ?>
    </body>
</html>