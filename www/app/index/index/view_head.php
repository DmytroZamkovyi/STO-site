<header>
    <div class="px-3 py-2 text-bg-dark border-bottom" style="vertical-align: middle;">
        <div class="container">
            <nav class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <figure class="me-lg-auto">
                    <blockquote class="blockquote" style="margin: 0;">
                        <h1><?= $label ?></h1>
                    </blockquote>
                    <p clas="lead" style="margin: 0;"><?= $account ?></p>
                </figure>
                <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                    <? for ($i = 0; $i < count($menu); $i++) { ?>
                        <li>
                            <? if ($menu[$i]['active']) { ?>
                                <p class="nav-link text-secondary" style="margin: 0;">
                                    <img class="bi d-block mx-auto mb-1" width="24" height="24" src=<?= $menu[$i]['img'] ?>></img>
                                    <?= $menu[$i]['name'] ?>
                                </p>
                            <? } else { ?>
                                <a href=<?= $menu[$i]['href'] ?> class="nav-link text-white">
                                    <img class="bi d-block mx-auto mb-1" width="24" height="24" src=<?= $menu[$i]['img'] ?>></img>
                                    <?= $menu[$i]['name'] ?>
                                </a>
                            <? } ?>
                        </li>
                    <? } ?>
                </ul>
            </nav>
        </div>
    </div>
</header>