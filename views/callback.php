<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yandex Metrica Api</title>
    <meta name="robots" content="noindex" />
    <meta name="googlebot" content="noindex" />
    <?= $view->render('head') ?>
    <?php $view->style('theme', 'system/theme:css/theme.css') ?>
    <?php $view->script('uikit-dist-js', 'app/assets/uikit/dist/js/uikit.min.js') ?>
    <?php $view->script('uikit-dist-js-icons', 'app/assets/uikit/dist/js/uikit-icons.min.js') ?>
    <style>
        .tm-button-copy {
            height: 40px;
            border-radius: 0px;
            background: #ffffff26 !important;
            color: #fff !important;
            border: 1px solid #ffffff1c;
        }
    </style>
</head>

<body>
    <div class="uk-section-primary">
        <div class="uk-flex uk-flex-center uk-flex-middle uk-text-center tm-background uk-height-viewport">
            <div class="uk-text-center uk-width-xlarge" uk-height-viewport="expand:true">
                <img width="800px" src="<?= $view->url()->getStatic($logo) ?>" alt="GreenCheap">
                <div class="uk-panel">
                    <p>Copy this token to use in metrica app.</p>
                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-expand">
                            <input type="text" class="uk-input uk-text-center uk-form-large uk-width-expand" id="accesstoken">
                        </div>
                        <div class="uk-width-auto">
                            <button onclick="copyFunction()" type="button" title="<?= __('Copy') ?>" class="uk-button uk-button-primary tm-button-copy" uk-tooltip><i uk-icon="copy"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-position-absolute uk-position-bottom-center uk-position-medium">
                <p class="developmentSignature">&hearts; Made by <a target="_blank" href="https://greencheap.net">GreenCheap</a> with love and caffeine.</p>
            </div>
        </div>
    </div>

    <script>
        var token = /access_token=([^&]+)/.exec(document.location.hash)[1];
        document.getElementById('accesstoken').value = token;
        function copyFunction() {
            var copyText = document.getElementById("accesstoken");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            UIkit.notification({
                message: '<?= __('Copied the access token') ?>',
                status: 'primary',
                pos: 'top-center',
                timeout: 5000
            });

        }
    </script>
</body>

</html>