<?php $view->extend('::base.html.php') ?>

<?php $view['slots']->set('title', 'Front - Puluprojects') ?>
<?php $view['slots']->set('description', 'Front of Puluprojects') ?>

<?php $view['slots']->start('body') ?>

<?php $currentLocale = $app->getRequest()->getLocale(); ?>

<div id="locale" data-locale="<?php echo $currentLocale ?>"></div>

<h1>Front <a style="float: right" href="<?php echo $view['router']->path('pulu_palsta_feed_articles') ?>" title="RSS Feed"><img src="<?php echo $view['assets']->getUrl('bundles/pulupalsta/images/icons/32_feed.png') ?>" alt="RSS Feed" /></a></h1>

<p>This is a collection of random projects. Current focus is on sport and travelling (April 2020). The most popular articles are in <strong>bold</strong> (top 25 %).</p>

<p><a href="https://palsta.pulu.org/fi/1-1500-litraa-pepsia-ja-sony-vaio-s5">1500 litraa Pepsiä ja Sony Vaio S5</a> was the first one. Other popular articles are <a href="https://palsta.pulu.org/fi/50">Finnish Municipal Border Signs</a> and <a href="https://palsta.pulu.org/en/60-elasto-mania-1995-2018">Elasto Mania (1995&ndash;2018)</a>. World travelling started from London in 2011. <a href="https://palsta.pulu.org/fi/90-vanhat-ravintolat">Old restaurants</a> is my own favourite.</p>

<!-- Popular/Recent articles -->
<div class="row">
    <div class="six columns" id="visited-articles">

<h3>Sport</h3>

<table class="wide">
<thead>
<tr>
    <th>Published</th>
    <th colspan="2">Article</th>
</tr>
</thead>
<tbody>
<?php foreach ($sportArticles as $article): ?>
<tr>
    <td><?php echo $article->getPublished()->format('Y-m-d'); ?></td>
    <td><a href='<?php echo $view['router']->path('pulu_palsta_article', array('article_number' => $article->getArticleNumber(), 'name' => $view['helper']->toFilename($article->getName()), '_locale' => $article->getLanguage())) ?>'><?php echo $article->getIsOneOfBest() ? '<strong>' : '' ?><?php echo $article->getName(); ?><?php echo $article->getIsOneOfBest() ? '</strong>' : '' ?></a></td>
    <td class="centered" style="width: 30px"> <img class="flag" src="<?php echo $view['assets']->getUrl('bundles/pulupalsta/images/icons/' . $article->getLanguage() . '.svg') ?>" alt="" /></td>
</tr>
<?php endforeach ?>
</tbody>
</table>
<p><br /></p>

<h3>Travel</h3>

<table class="wide">
<thead>
<tr>
    <th>Published</th>
    <th colspan="2">Article</th>
</tr>
</thead>

<tbody>
<tr>
    <td>TBA</td>
    <td>Kaukana Kaakossa (Uusi-Seelanti, Singapore, Vietnam)</td>
    <td>?</td>
</tr>
<?php $carbonCss = ''; ?>
<?php $isCarbonStartPrinted = false; ?>
<?php $isCarbonEndPrinted = false; ?>
<?php foreach ($travellingArticles as $article): ?>
<?php if (1 == 2 && $article->getPublished()->format('Y') === '2015' && !$isCarbonEndPrinted): ?>
<tr>
    <td colspan="3" class="zero-carbon-start-separator">ZERO CARBON SINCE 2016</td>
</tr>
<?php $isCarbonEndPrinted = true; ?>
<?php $carbonCss = ''; //' class="carbon"'; ?>
<?php endif; ?>
<tr<?php echo $carbonCss ?>>
    <td><?php echo $article->getPublished()->format('Y-m-d') ?></td>
    <td><a href='<?php echo $view['router']->path('pulu_palsta_article', array('article_number' => $article->getArticleNumber(), 'name' => $view['helper']->toFilename($article->getName()), '_locale' => $article->getLanguage())) ?>'><?php echo $article->getIsOneOfBest() ? '<strong>' : '' ?><?php echo $article->getName(); ?><?php echo $article->getIsOneOfBest() ? '</strong>' : '' ?></a></td>
    <td class="centered" style="width: 30px"> <img class="flag" src="<?php echo $view['assets']->getUrl('bundles/pulupalsta/images/icons/' . $article->getLanguage() . '.svg') ?>" alt="" /></td>
</tr>
<?php endforeach ?>
</tbody>
</table>
    <div class="six columns">
        <p></p>
    </div>

    </div>
    <div class="six columns" id="recent-articles">

<h3>Nutrition</h3>

<table class="wide">
<thead>
<tr>
    <th>Published</th>
    <th colspan="2">Article</th>
</tr>
</thead>
<tbody>
<?php foreach ($nutritionArticles as $article): ?>
<tr>
    <td><?php echo $article->getPublished()->format('Y-m-d'); ?></td>
    <td><a href='<?php echo $view['router']->path('pulu_palsta_article', array('article_number' => $article->getArticleNumber(), 'name' => $view['helper']->toFilename($article->getName()), '_locale' => $article->getLanguage())) ?>'><?php echo $article->getIsOneOfBest() ? '<strong>' : '' ?><?php echo $article->getName(); ?><?php echo $article->getIsOneOfBest() ? '</strong>' : '' ?></a></td>
    <td class="centered" style="width: 30px"> <img class="flag" src="<?php echo $view['assets']->getUrl('bundles/pulupalsta/images/icons/' . $article->getLanguage() . '.svg') ?>" alt="" /></td>
</tr>
<?php endforeach ?>
</tbody>
</table>
<p><br /></p>

<h3>Research</h3>

<table class="wide">
<thead>
<tr>
    <th>Published</th>
    <th colspan="2">Article</th>
</tr>
</thead>
<tbody>
<?php $carbonCss = ''; ?>
<?php $isCarbonStartPrinted = false; ?>
<?php $isCarbonEndPrinted = false; ?>
<?php foreach ($researchArticles as $article): ?>
<?php if (1 == 2 && $article->getPublished()->format('Y') === '2015' && !$isCarbonEndPrinted): ?>
<tr>
    <td colspan="3" class="zero-carbon-start-separator">ZERO CARBON SINCE 2016</td>
</tr>
<?php $isCarbonEndPrinted = true; ?>
<?php $carbonCss = ''; //' class="carbon"'; ?>
<?php endif; ?>
<tr<?php echo $carbonCss ?>>
    <td><?php echo $article->getPublished()->format('Y-m-d'); ?></td>
    <td><a href='<?php echo $view['router']->path('pulu_palsta_article', array('article_number' => $article->getArticleNumber(), 'name' => $view['helper']->toFilename($article->getName()), '_locale' => $article->getLanguage())) ?>'><?php echo $article->getIsOneOfBest() ? '<strong>' : '' ?><?php echo $article->getName(); ?><?php echo $article->getIsOneOfBest() ? '</strong>' : '' ?></a></td>
    <td class="centered" style="width: 30px"> <img class="flag" src="<?php echo $view['assets']->getUrl('bundles/pulupalsta/images/icons/' . $article->getLanguage() . '.svg') ?>" alt="" /></td>
</tr>
<?php endforeach ?>
</tbody>
</table>
<p></p>

    </div>
    <div class="six columns">
        <p></p>
    </div>
</div><!-- Popular/Recent articles ends -->

<?php $view['slots']->stop() ?>
