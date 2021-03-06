<?php $view->extend('PuluPalstaBundle:Admin:base.html.php') ?>

<?php $view['slots']->start('body') ?>

<?php if ($article->getId() > 0): ?>
<ul class="breadcrumbs">
    <li><a href="<?php echo $view['router']->path('pulu_palsta_admin') ?>">Etusivu</a></li>
    <li><a href="<?php echo $view['router']->path('pulu_palsta_admin_article') ?>">Artikkelit</a></li>
    <li><a href="<?php echo $view['router']->path('pulu_palsta_admin_article_edit', array('id' => $article->getId())) ?>" class="current"><?php echo $article->getName() ?></a></li>
</ul>
<h1><?php echo $article->getName() ?></h1>
<?php $formUrl = 'pulu_palsta_admin_article_edit'; ?>
<?php else: ?>
<ul class="breadcrumbs">
    <li><a href="<?php echo $view['router']->path('pulu_palsta_admin') ?>">Etusivu</a></li>
    <li><a href="<?php echo $view['router']->path('pulu_palsta_admin_article') ?>">Artikkelit</a></li>
    <li><a href="<?php echo $view['router']->path('pulu_palsta_admin_article_create') ?>" class="current">Luo uusi</a></li>
</ul>
<h1>Luo uusi artikkeli</h1>
<?php $formUrl = 'pulu_palsta_admin_article_create'; ?>
<?php endif ?>

<ul class="tabs-content" id="admin-switch-language">
    <li class="active">
    <dl class="tabs pill">
        <dd class="active"><a href="javascript:void(0);" class="switch-language" data-to="fi">suomeksi</a></dd>
        <dd><a href="javascript:void(0);" class="switch-language" data-to="en">englanniksi</a></dd>
    </dl>
    </li>
</ul>

<form action="<?php echo $view['router']->path($formUrl, array('id' => $article->getId())) ?>" method="post">
    <?php $view['form']->setTheme($form, array('PuluPalstaBundle:Form')) ;?>

    <?php if (! empty($form['localizations'])): ?>
        <?php foreach ($form['localizations'] as $row): ?>
            <div id="language-<?php echo $row['language']->vars['value'] ?>">
            <?php echo $view['form']->row($row['language']) // skip printing ?>
            <?php echo $view['form']->row($row['name']) ?>
            <?php echo $view['form']->row($row['teaser']) ?>
            <?php echo $view['form']->row($row['body']) ?>
            </div>
        <?php endforeach ?>
    <?php endif ?>

    <div class="row">
        <div class="two columns">
        <?php echo $view['form']->row($form['article_number']) ?>
        </div>
        <div class="one columns">
        <?php echo $view['form']->row($form['type']) ?>
        </div>
        <div class="one columns">
        <?php echo $view['form']->row($form['language']) ?>
        </div>
        <div class="one columns">
        <?php echo $view['form']->row($form['access']) ?>
        </div>
        <div class="one columns">
        <?php echo $view['form']->row($form['is_draft']) ?>
        </div>
        <div class="one columns">
        <?php echo $view['form']->row($form['is_commentable']) ?>
        </div>
        <div class="four columns">
        <?php echo $view['form']->row($form['use_translator']) ?>
        </div>
    </div>

    <div class="row">
        <div class="three columns">
        <?php echo $view['form']->row($form['written_at']) ?>
        </div>
        <div class="three columns">
        <?php echo $view['form']->row($form['started']) ?>
        </div>
        <div class="three columns">
        <?php echo $view['form']->row($form['published']) ?>
        </div>
        <div class="three columns">
        <?php echo $view['form']->row($form['modified_public']) ?>
        </div>
    </div>

    <div class="row">
        <div class="twelve columns">
        <?php echo $view['form']->row($form['teaser_image']) ?>
        </div>
    </div>

    <div class="row">
        <div class="nine columns">
        </div>
        <div class="three columns">
        <label>Avainsanat</label>
        </div>
    </div>
    <?php for($i = 0; $i <= 100; $i++): ?>
        <?php if (! isset($form['keyword_' . $i . '_id'])): ?>
            <?php break; ?>
        <?php endif ?>
    <div class="row">
        <div class="nine columns">
        </div>
        <div class="two columns">
        <?php echo $view['form']->row($form['keyword_' . $i . '_id']) ?>
        </div>
        <div class="one columns">
        <?php echo $view['form']->row($form['keyword_' . $i . '_weight']) ?>
        </div>
    </div>
    <?php endfor ?>

    <?php echo $view['form']->rest($form) ?>
    <?php if ($article->getId() > 0): ?>
    <input type="hidden" name="id" value="<?php echo $article->getId() ?>" />
    <?php endif ?>
    <input class="button" type="submit" value="Tallenna" />
    <?php if ($article->getId() > 0): ?>
    <input class="alert button right" id="deleteConfirmation" type="submit" value="Poista" />
    <?php endif ?>
</form>

<?php if ($article->getId() > 0): ?>
<h2>Toiminnot</h2>
<ul>
    <li><a target="_blank" href='<?php echo $view['router']->path('pulu_palsta_article_without_name', array('article_number' => $article->getArticleNumber(), '_locale' => $article->getLanguage())) ?>'>Artikkelisivu (julkinen)</li>
    <li><a href="<?php echo $view['router']->path('pulu_palsta_admin_article_history', array('id' => $article->getId(), 'language' => 'fi')) ?>">Historia (fi)</a></li>
    <li><a href="<?php echo $view['router']->path('pulu_palsta_admin_article_history', array('id' => $article->getId(), 'language' => 'en')) ?>">Historia (en)</a></li>
    <?php foreach ($article->getModules() as $module): ?>
    <li>Moduuli: <a href="<?php echo $view['router']->path('pulu_palsta_admin_module_use', array('id' => $module->getId())) ?>"><?php echo $module->getName() ?></li>
    <?php endforeach ?>
</ul>
<?php endif ?>

<?php $view['slots']->stop('body') ?>

<?php $view['slots']->start('reveal') ?>

<div id="deleteConfirmationModal" class="reveal-modal small">
    <h4>Oletko varma?</h4>
    <form action="<?php echo $view['router']->path($formUrl, array('id' => $article->getId())) ?>" method="post">
        <input class="secondary button close" type="submit" value="Peruuta" />
        <input class="alert button right" name="delete" type="submit" value="Kyllä" />
    </form>
    <a class="close-reveal-modal">&#215;</a>
</div>

<?php $view['slots']->stop() ?>
