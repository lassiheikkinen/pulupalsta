<?php $view->extend('::base.html.php') ?>

<?php global $currentLocale ?>
<?php $currentLocale = $app->getRequest()->getLocale(); ?>

<?php $view['slots']->set('title', $article->getName($currentLocale) . ' - Pulupalsta') ?>

<?php $view['slots']->start('body') ?>

<style type="text/css">
/* Old Puluprojects settings */
ul          {list-style: square; margin: 5px 0px 0px 30px}
</style>

<?php if (! $article->getIsPublic()): ?>
    <div class="alert-box secondary" id="hidden-article">PIILOTETTU</div>
<? endif ?>

<h1><?php echo $article->getName($currentLocale) ?></h1>

<div id="article-metadata">
<strong><?php echo $view['translator']->trans('Arvosana') ?>:</strong> <?php echo $article->getRating() ?>/5
&nbsp;&nbsp;<strong><?php echo $view['translator']->trans('Vierailuja') ?>:</strong> <?php echo $article->getVisits() ?>
&nbsp;&nbsp;<strong><?php echo $view['translator']->trans('Julkaistu') ?>:</strong> <?php echo $article->getPublished()->format('Y-m-d') ?>
&nbsp;&nbsp;<strong><?php echo $view['translator']->trans('Muokattu') ?>:</strong> <?php echo $article->getModifiedPublic()->format('Y-m-d') ?><br />
<strong><?php echo $view['translator']->trans('Avainsanat') ?>:</strong>
<?php $printKeywords = array(); ?>
<?php foreach ($article_keywords as $article_keyword): ?>
    <? $articleObjects = $article_keyword->getArticles();
    $count = 0;
    foreach ($articleObjects as $articleObject) {
        if ($articleObject->getArticle()->isPublic()) {
            $count++;
        }
    } ?>
    <? if ($count > 1): ?>
        <?php $printKeywords[] = '<a href="' . $view['router']->generate('pulu_palsta_index') . '#' . $article_keyword->getName() . '"><em>' . $article_keyword->getName($currentLocale) . '</em></a>'; ?>
    <? else: ?>
        <?php $printKeywords[] = '<em>' . $article_keyword->getName($currentLocale) . '</em>'; ?>
    <? endif ?>
<?php endforeach ?>
<?php echo implode(', ', $printKeywords) ?>
</div>

<?php $body = $article->getBody($currentLocale); ?>
<?php $route_params = $app->getRequest()->get('_route_params'); ?>

<? if (empty($body)): ?>
    <? if ($currentLocale == 'fi'): ?>
        <? $body = $article->getBody('en'); ?>
<div class="alert-box">Valitettavasti artikkelista ei löydy suomenkielistä käännöstä<?php if ($article->getUseTranslator() === true): ?>, mutta ainahan voit avata sivun <a href="http://translate.google.com/translate?sl=en&tl=fi&ie=UTF-8&u=<?php echo urlencode($view['router']->generate($app->getRequest()->get('_route'), array_merge($route_params, array('_locale' => 'en')), true)) ?>">Google Translatorin</a> kautta<? endif ?>.</div>
    <? else: ?>
        <? $body = $article->getBody('fi'); ?>
<div class="alert-box">Unfortunately an English translation doesn't exist<?php if ($article->getUseTranslator() === true): ?> but you can probably get a clue from looking at the <a href="http://translate.google.com/translate?sl=fi&tl=en&ie=UTF-8&u=<?php echo urlencode($view['router']->generate($app->getRequest()->get('_route'), array_merge($route_params, array('_locale' => 'fi')), true)) ?>">Google Translator</a> version<? endif ?>.</div>
    <? endif ?>
<? endif ?>

<?php
// Old Puluprojects functions

global $articleNumber, $mediaPath, $mediaUrl;
$articleNumber = $article->getArticleNumber();
$mediaPath = $view->container->getParameter('media.path');
$mediaUrl = $view->container->getParameter('media.url');

/*function toFilename($string) {
    $conversion_array = array(
        'ä' => 'a', 'Ä' => 'A', 'Ö' => 'O', 'ö' => 'o', 'å' => 'a', 'Å' => 'A',
        'á' => 'a', 'Á' => 'a', 'ó' => 'o', 'Ó' => 'o', 'ñ' => 'n', 'Ñ' => 'N',
        'š' => 's', 'Š' => 's', '€' => 'e', 'ž' => 'z', 'Ž' => 'z'
        );

    $string = strtr($string, $conversion_array);
    $string = toWord(str_replace(' ', '-', $string));
    $string = preg_replace('/-{2,}/', '-', strtolower($string));
    $string = trim(str_replace('-', ' ', $string));
    $string = str_replace(' ', '-', $string);
    $string = urlencode($string);
    $string = preg_replace('/%../', '', urlencode($string));
    return $string;
}*/

/*function toWord($str) {
    static $bad = array(
        '\'', '"', '<', '>', '{', '}', '[', ']', '`', '!', '@', '#',
        '$', '%', '^', '&', '*', '(', ')', '=', '+', '|', '/', '\\',
        ';', ':', ',', '?', '/', ' '
    );
    return str_replace($bad, '', $str);
}*/

function htmlize($line, $linebreak = true) {
    $return = '&lt;' . $line . '&gt;';
    if ($linebreak) {
        $return .= "\n";
    }
    return $return;
}

function getImage($filename, $width = null, $height = null) {
    global $articleNumber;
    global $mediaPath;
    $dir = 'files/' . $articleNumber . '/img/';

    // return original
    if (empty($width) && empty($height)) {
        return $dir . $filename;
    }

    $basepath = $mediaPath . $dir;
    $dimensions = strval($width) . 'x' . strval($height);

    $fileparts = explode('.', $filename);
    $extension = array_pop($fileparts);
    $source = $basepath . $filename;
    $destination = $basepath . $dimensions . '/' . $filename;

    if (! file_exists($source)) {
        return false;
    }

    if (! file_exists($destination)) {
        if (! file_exists(dirname($destination))) {
            mkdir(dirname($destination), 0777, true);
        }

        if (strtolower($extension) == 'png') {
            $source_handle = imagecreatefrompng($source);
        } else {
            $source_handle = imagecreatefromjpeg($source);
        }

        $source_width = imageSX($source_handle);
        $source_height = imageSY($source_handle);
        $source_ratio = $source_width / $source_height;

        if (! empty($width) && ! empty($height)) {                
            $destination_ratio = $width / $height;
            if ($source_ratio == $destination_ratio) {
                $destination_width = $width;
                $destination_height = $height;
            } else if ($source_ratio < $destination_ratio) {
                $destination_height = $height;
                $destination_width = round($height * $source_ratio);
            } else {
                $destination_width = $width;
                $destination_height = round($width / $source_ratio);
            }
        } else if (empty($width)) {
            $destination_height = $height;
            $destination_width = round($height * $source_ratio);
        } else if (empty($height)) {
            $destination_width = $width;
            $destination_height = round($width / $source_ratio);
        }
        $destination_width = max(1, $destination_width);
        $destination_height = max(1, $destination_height);
        $destination_handle = ImageCreateTrueColor($destination_width, $destination_height);
        imagecopyresampled($destination_handle, $source_handle, 0, 0, 0, 0, $destination_width, $destination_height, $source_width, $source_height);
        if (strtolower($extension) == 'png') {
            imagepng($destination_handle, $destination, 0);
        } else {
            imagejpeg($destination_handle, $destination, 90);
        }
        imagedestroy($destination_handle);
        imagedestroy($source_handle);
    }

    return $dir . $dimensions . '/' . $filename;
}

function displayImage($filename, $width = null, $height = null, $caption = "", $alt = "", $is_thumb = false, $thumb_identifier = null) {
    global $currentLocale;
    global $mediaUrl;

    $original_url = getImage($filename);
    $display_url = getImage($filename, $width, $height);
    $mediaPath = $mediaUrl;
    $hash = md5($original_url);
    $shortHash = substr($hash, 0, 6);

    if (empty($original_url) || empty($display_url)) {
        return '';
    }

    $captionWithoutHtml = $caption;
    $originalText = $currentLocale == 'fi' ? 'originaali' : 'original';
    $caption = '<span class="right">(<a href="' . $mediaPath . $original_url . '">' . $originalText . '</a>, <a href="#img-' . $shortHash . '">§</a>)</span> ' . $caption;

    $out = '<div class="centered imgContainer" id="img-' . $shortHash . '" style="';
    $dimensions = getimagesize($mediaPath . $display_url);
    $width = ! empty($dimensions[0]) ? $dimensions[0] : $width;
    if (! empty($width)) {
        $out .= 'width: ' . ($width + 8). 'px;';
    }
    if ($is_thumb) {
        $out .= 'margin: 0px 10px 10px 0px; display: inline-block;';
    }
    $rel = ! empty($thumb_identifier) ? 'fancybox-group-' . substr($thumb_identifier, 0, 10) : 'fancybox-main';
    $out .= '"><a href="' . $mediaPath . $original_url . '" rel="' . $rel . '" class="fancybox" title="' . $captionWithoutHtml . '" data-title-id="fancybox-title-' . $hash . '"><img';
    if ($is_thumb) {
        $out .= ' style="margin: 0px;"';
    }
    if (is_null($alt)) {
        $alt = mb_substr($desc, 40);
    }
    $out .= ' class="js-lazy-image" data-src="' . $mediaPath . $display_url . '" alt="' . $alt . '" /></a>';
    if (! $is_thumb) {
        $out .= '<p>' . $captionWithoutHtml . '</p>';
    }
    $out .= '</div>';
    
    // html supported caption
    $out .= '<div id="fancybox-title-' . $hash . '" style="display: none">' . $caption . '</div>';

    return $out;
}

function displayThumbs($images = array()) {
    $out = '<div class="thumbsContainer">';
    $thumb_identifier = md5(serialize($images));
    foreach ($images as $image) {
        $param1 = isset($image[0]) ? $image[0] : '';
        $param2 = isset($image[1]) ? $image[1] : '';
        $param3 = isset($image[2]) ? $image[2] : '';
        $param4 = isset($image[3]) ? $image[3] : '';
        $param5 = isset($image[4]) ? $image[4] : '';
        $out .= displayImage($param1, $param2, $param3, $param4, $param5, true, $thumb_identifier);
    }
    $out .= '</div><div style="clear: both"></div>';
    return $out;
}
?>

<? eval('?>' . $body . '<?php '); ?>

<h2 style="margin-bottom: 5px"><?php echo $view['translator']->trans('Arvioi lukemasi') ?></h2>

<div id="rating" data-rating="<?php echo $rating ?>">
    <div></div><div></div><div></div><div></div><div></div>
</div>
<div id='info'></div>
<div id="article_id" data-id="<?php echo $article->getId() ?>"></div>
<div id="locale" data-locale="<?php echo $currentLocale ?>"></div>

<a name="kommentointi"></a>
<div <?php echo empty($comments) ? 'style="display: none"' : '' ?>>
<h2><?php echo $view['translator']->trans('Kommentit') ?></h2>
<table class="wide" id="comments">
<thead>
<tr>
    <th><?php echo $view['translator']->trans('Kirjoittaja') ?></th>
    <th><?php echo $view['translator']->trans('Kommentti') ?></th>
</tr>
</thead>
<tbody>
<? foreach ($comments as $comment): ?>
<tr>
    <td style="width: 12%"><strong><?php echo $comment->getAuthorName() ?></strong><br /><small><?php echo $comment->getCreated()->format('Y-m-d H:i') ?></small></td>
    <td><?php echo(nl2br($view['helper']->convertUrlsToLinks(htmlspecialchars($comment->getBody())))) ?></td>
</tr>
<? endforeach ?>

</tbody>
</table>
</div>
<? //endif ?>

<h3><?php echo $view['translator']->trans('Kirjoita uusi kommentti') ?></h3>

<form id="articleComment" action="<?php echo $view['router']->generate('pulu_palsta_article_comment') ?>" method="post" <?php echo $view['form']->enctype($form) ?> >
    <?php $view['form']->setTheme($form, array('PuluPalstaBundle:Form')) ;?>
    <div class="row">
    <div class="six columns">
    <?php echo $view['form']->row($form['body']) ?>
    </div>
    <div class="six columns">
    <?php echo $view['form']->row($form['author_name']) ?>
    <?php echo $view['form']->row($form['safety_question']) ?>
    <?php echo $view['form']->rest($form) ?>
    <input type="hidden" name="article_id" value="<?php echo $article->getId() ?>" />
    <p><input class="button" type="submit" value="<?php echo $view['translator']->trans('Lähetä') ?>" /></p>
    </div>
    </div>
</form>

<?php $view['slots']->stop() ?>
