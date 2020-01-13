const mix = require("laravel-mix");

mix.copy("resources/webfonts", "./public/webfonts", false)
    .copy("resources/images", "./public/images", false)
    .js("resources/js/turbolinks.js", "./public/js/turbolinks.js")
    .styles(
        ["resources/css/font-awesome.css", "resources/css/adminlte.css", "resources/css/icheck-bootstrap.css", "resources/css/custom.css", "resources/css/google-font.css"],
        "public/css/app.css"
    )
    .js(["resources/js/app.js"], "public/js");
