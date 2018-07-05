{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-44b54d030083405a495fbc45590228ab7bdd02e1";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "44b54d030083405a495fbc45590228ab7bdd02e1";
        sha256 = "0zxhvfbrllc0xsw3rqggwbxk7nqz7cgs7mncjn2wys4yb7aivmh7";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-365ecf3d2f5c0046d1bd7860a1efc009164ffb3b";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "365ecf3d2f5c0046d1bd7860a1efc009164ffb3b";
        sha256 = "0nl4z9l827y0aizpzhs5dpjwzkgjhymf9z140xbvyj4ckjcmrzp5";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-7e441d9e3b40c82ef695a564f4c245fc3b6839f3";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "7e441d9e3b40c82ef695a564f4c245fc3b6839f3";
        sha256 = "0gmiqc9wwfxq5nx59ab1sjs5gky0v34a05khym5wsk3vfpc0y499";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-8706a46b34a9350df6b1cd14504d037dc5a40bad";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "8706a46b34a9350df6b1cd14504d037dc5a40bad";
        sha256 = "12pqdfs5f3nqvbjfliigqfnn146vfvfkj440fnzabml8x40nxyzw";
      };
    };
  };
  devPackages = {
    "cilex/cilex" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "cilex-cilex-7acd965a609a56d0345e8b6071c261fbdb926cb5";
        src = fetchurl {
          url = https://api.github.com/repos/Cilex/Cilex/zipball/7acd965a609a56d0345e8b6071c261fbdb926cb5;
          sha256 = "0hi8xfwkj7bj15mpaqxj06bngz4gk2idhkc9yxxr5k4x72swvhzp";
        };
      };
    };
    "cilex/console-service-provider" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "cilex-console-service-provider-25ee3d1875243d38e1a3448ff94bdf944f70d24e";
        src = fetchurl {
          url = https://api.github.com/repos/Cilex/console-service-provider/zipball/25ee3d1875243d38e1a3448ff94bdf944f70d24e;
          sha256 = "1g9zgx1hplkbhhqsci5l4m9j7mi6w6j6b32bg0sn3b9q3510damg";
        };
      };
    };
    "container-interop/container-interop" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "container-interop-container-interop-79cbf1341c22ec75643d841642dd5d6acd83bdb8";
        src = fetchurl {
          url = https://api.github.com/repos/container-interop/container-interop/zipball/79cbf1341c22ec75643d841642dd5d6acd83bdb8;
          sha256 = "1pxm461g5flcq50yabr01nw8w17n3g7klpman9ps3im4z0604m52";
        };
      };
    };
    "doctrine/annotations" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-annotations-c7f2050c68a9ab0bdb0f98567ec08d80ea7d24d5";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/annotations/zipball/c7f2050c68a9ab0bdb0f98567ec08d80ea7d24d5;
          sha256 = "0b80xpqd3j99xgm0c41kbgy0k6knrfnd29223c93295sb12112g7";
        };
      };
    };
    "doctrine/instantiator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-instantiator-185b8868aa9bf7159f5f953ed5afb2d7fcdc3bda";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/instantiator/zipball/185b8868aa9bf7159f5f953ed5afb2d7fcdc3bda;
          sha256 = "1mah9a6mb30qad1zryzjain2dxw29d8h4bjkbcs3srpm3p891msy";
        };
      };
    };
    "doctrine/lexer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-lexer-83893c552fd2045dd78aef794c31e694c37c0b8c";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/lexer/zipball/83893c552fd2045dd78aef794c31e694c37c0b8c;
          sha256 = "0cyh3vwcl163cx1vrcwmhlh5jg9h47xwiqgzc6rwscxw0ppd1v74";
        };
      };
    };
    "erusev/parsedown" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "erusev-parsedown-92e9c27ba0e74b8b028b111d1b6f956a15c01fc1";
        src = fetchurl {
          url = https://api.github.com/repos/erusev/parsedown/zipball/92e9c27ba0e74b8b028b111d1b6f956a15c01fc1;
          sha256 = "1v7n9niys176acs8cn9lh6qlwaw62hmsvm76384k6jg24c1pyp0k";
        };
      };
    };
    "herrera-io/json" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "herrera-io-json-60c696c9370a1e5136816ca557c17f82a6fa83f1";
        src = fetchurl {
          url = https://api.github.com/repos/kherge-php/json/zipball/60c696c9370a1e5136816ca557c17f82a6fa83f1;
          sha256 = "1bx6rnrhvfn0ia2c95nhjk2mci0c4aj2s7ijqv0ihvda54abpws0";
        };
      };
    };
    "herrera-io/phar-update" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "herrera-io-phar-update-00a79e1d5b8cf3c080a2e3becf1ddf7a7fea025b";
        src = fetchurl {
          url = https://api.github.com/repos/kherge-abandoned/php-phar-update/zipball/00a79e1d5b8cf3c080a2e3becf1ddf7a7fea025b;
          sha256 = "0dz3pbba9b6x6l8rba36mxa75dy131j3pvjbgads5xibdzb6zsj0";
        };
      };
    };
    "jms/metadata" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "jms-metadata-6a06970a10e0a532fb52d3959547123b84a3b3ab";
        src = fetchurl {
          url = https://api.github.com/repos/schmittjoh/metadata/zipball/6a06970a10e0a532fb52d3959547123b84a3b3ab;
          sha256 = "0bmmgwgnphlsp5da9xjxmwky837k8fqyqrwcrfi37c2c32qm1h68";
        };
      };
    };
    "jms/parser-lib" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "jms-parser-lib-c509473bc1b4866415627af0e1c6cc8ac97fa51d";
        src = fetchurl {
          url = https://api.github.com/repos/schmittjoh/parser-lib/zipball/c509473bc1b4866415627af0e1c6cc8ac97fa51d;
          sha256 = "1jkgihdxc28vklqzp7zd6wvi6q9dsym1q8cig9x6rm0ws51fns85";
        };
      };
    };
    "jms/serializer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "jms-serializer-e7c53477ff55c21d1b1db7d062edc050a24f465f";
        src = fetchurl {
          url = https://api.github.com/repos/schmittjoh/serializer/zipball/e7c53477ff55c21d1b1db7d062edc050a24f465f;
          sha256 = "0r2j91w8cjaaqfsg81l30cad8cw6ndgh38l4dk5x10az5mm086mp";
        };
      };
    };
    "justinrainbow/json-schema" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "justinrainbow-json-schema-cc84765fb7317f6b07bd8ac78364747f95b86341";
        src = fetchurl {
          url = https://api.github.com/repos/justinrainbow/json-schema/zipball/cc84765fb7317f6b07bd8ac78364747f95b86341;
          sha256 = "0hgk8yqis25ymjcn1nhvdmbk5rkbr0qdz4jqm84zr1rkk2v5ckv9";
        };
      };
    };
    "kherge/version" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "kherge-version-f07cf83f8ce533be8f93d2893d96d674bbeb7e30";
        src = fetchurl {
          url = https://api.github.com/repos/kherge-abandoned/Version/zipball/f07cf83f8ce533be8f93d2893d96d674bbeb7e30;
          sha256 = "18l6nv6n6m85ywcmzf1d7xqjb4by26fzyjhkfvkj82rahxqji036";
        };
      };
    };
    "monolog/monolog" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "monolog-monolog-fd8c787753b3a2ad11bc60c063cff1358a32a3b4";
        src = fetchurl {
          url = https://api.github.com/repos/Seldaek/monolog/zipball/fd8c787753b3a2ad11bc60c063cff1358a32a3b4;
          sha256 = "0avf3y8raw23krwdb7kw9qb5bsr5ls4i7qd2vh7hcds3qjixg3h9";
        };
      };
    };
    "nikic/php-parser" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nikic-php-parser-f78af2c9c86107aa1a34cd1dbb5bbe9eeb0d9f51";
        src = fetchurl {
          url = https://api.github.com/repos/nikic/PHP-Parser/zipball/f78af2c9c86107aa1a34cd1dbb5bbe9eeb0d9f51;
          sha256 = "008iv40q92cldbfqs5bc9s11i0fpycjafv7s4wk4y6h5wrbf34qk";
        };
      };
    };
    "phpcollection/phpcollection" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpcollection-phpcollection-f2bcff45c0da7c27991bbc1f90f47c4b7fb434a6";
        src = fetchurl {
          url = https://api.github.com/repos/schmittjoh/php-collection/zipball/f2bcff45c0da7c27991bbc1f90f47c4b7fb434a6;
          sha256 = "0bfbg7bs7q3wmyl3kp3vqshcj0pklj14z1vlxk4ymxrjzxwmb8my";
        };
      };
    };
    "phpdocumentor/fileset" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpdocumentor-fileset-bfa78d8fa9763dfce6d0e5d3730c1d8ab25d34b0";
        src = fetchurl {
          url = https://api.github.com/repos/phpDocumentor/Fileset/zipball/bfa78d8fa9763dfce6d0e5d3730c1d8ab25d34b0;
          sha256 = "0ncvq8zfnr3azzpw0navm2lk9w0dskk7mar2m4immzxyip00gp89";
        };
      };
    };
    "phpdocumentor/graphviz" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpdocumentor-graphviz-a906a90a9f230535f25ea31caf81b2323956283f";
        src = fetchurl {
          url = https://api.github.com/repos/phpDocumentor/GraphViz/zipball/a906a90a9f230535f25ea31caf81b2323956283f;
          sha256 = "06y7pha2nrki27k2jdpb4l1px5ngpwlwrmgg6lcxlzp4brf1q7ds";
        };
      };
    };
    "phpdocumentor/phpdocumentor" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpdocumentor-phpdocumentor-be607da0eef9b9249c43c5b4820d25d631c73667";
        src = fetchurl {
          url = https://api.github.com/repos/phpDocumentor/phpDocumentor2/zipball/be607da0eef9b9249c43c5b4820d25d631c73667;
          sha256 = "1gkvxw5q8fi2rpvc2g31n3bpywwcxjx2p1ickkd40bnvj9qw5wh1";
        };
      };
    };
    "phpdocumentor/reflection" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpdocumentor-reflection-793bfd92d9a0fc96ae9608fb3e947c3f59fb3a0d";
        src = fetchurl {
          url = https://api.github.com/repos/phpDocumentor/Reflection/zipball/793bfd92d9a0fc96ae9608fb3e947c3f59fb3a0d;
          sha256 = "1k2hbcjkiyyb8yzw9682i4i0bnrdzfapj6qhh4idn2d80bqzgkir";
        };
      };
    };
    "phpdocumentor/reflection-docblock" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpdocumentor-reflection-docblock-e6a969a640b00d8daa3c66518b0405fb41ae0c4b";
        src = fetchurl {
          url = https://api.github.com/repos/phpDocumentor/ReflectionDocBlock/zipball/e6a969a640b00d8daa3c66518b0405fb41ae0c4b;
          sha256 = "0hgrmgcdi9qadwsjcplg6lfjjwdjfajd2vm97bd0jkh0ykrxqghs";
        };
      };
    };
    "phpoption/phpoption" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpoption-phpoption-94e644f7d2051a5f0fcf77d81605f152eecff0ed";
        src = fetchurl {
          url = https://api.github.com/repos/schmittjoh/php-option/zipball/94e644f7d2051a5f0fcf77d81605f152eecff0ed;
          sha256 = "0vl5di2k4fypy1698hl86yjchlkcc8wacrgzlk6z66szf9xnn3nc";
        };
      };
    };
    "pimple/pimple" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "pimple-pimple-2019c145fe393923f3441b23f29bbdfaa5c58c4d";
        src = fetchurl {
          url = https://api.github.com/repos/silexphp/Pimple/zipball/2019c145fe393923f3441b23f29bbdfaa5c58c4d;
          sha256 = "17rnqcfmdr7lfvqprcnn3cbldj37gi9d7g8rjz6lzr813cj9q826";
        };
      };
    };
    "psr/cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-cache-d11b50ad223250cf17b86e38383413f5a6764bf8";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/cache/zipball/d11b50ad223250cf17b86e38383413f5a6764bf8;
          sha256 = "06i2k3dx3b4lgn9a4v1dlgv8l9wcl4kl7vzhh63lbji0q96hv8qz";
        };
      };
    };
    "psr/container" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-container-b7ce3b176482dbbc1245ebf52b181af44c2cf55f";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/container/zipball/b7ce3b176482dbbc1245ebf52b181af44c2cf55f;
          sha256 = "0rkz64vgwb0gfi09klvgay4qnw993l1dc03vyip7d7m2zxi6cy4j";
        };
      };
    };
    "psr/log" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-log-4ebe3a8bf773a19edfe0a84b6585ba3d401b724d";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/log/zipball/4ebe3a8bf773a19edfe0a84b6585ba3d401b724d;
          sha256 = "1mlcv17fjw39bjpck176ah1z393b6pnbw3jqhhrblj27c70785md";
        };
      };
    };
    "psr/simple-cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-simple-cache-408d5eafb83c57f6365a3ca330ff23aa4a5fa39b";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/simple-cache/zipball/408d5eafb83c57f6365a3ca330ff23aa4a5fa39b;
          sha256 = "1djgzclkamjxi9jy4m9ggfzgq1vqxaga2ip7l3cj88p7rwkzjxgw";
        };
      };
    };
    "seld/jsonlint" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "seld-jsonlint-d15f59a67ff805a44c50ea0516d2341740f81a38";
        src = fetchurl {
          url = https://api.github.com/repos/Seldaek/jsonlint/zipball/d15f59a67ff805a44c50ea0516d2341740f81a38;
          sha256 = "1yd37g3c9gjk6d0qpd12xrlgd9mfvndv69h41n6fasvr1ags4ya1";
        };
      };
    };
    "symfony/config" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-config-ecacddeaf76732231eea1657f6f5b062dade94c9";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/config/zipball/ecacddeaf76732231eea1657f6f5b062dade94c9;
          sha256 = "10dy35zkm5nmq1ik7gnrrz49xgv54pfzih5xvcjjqmnvlycb0sli";
        };
      };
    };
    "symfony/console" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-console-932d1e4f7f33ee37d3534f5f452474daa66283c2";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/console/zipball/932d1e4f7f33ee37d3534f5f452474daa66283c2;
          sha256 = "08ik7rm1z8k1ar19z826rj9j23dy4wf43n0k79rzrbm5mj7nq78f";
        };
      };
    };
    "symfony/debug" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-debug-697c527acd9ea1b2d3efac34d9806bf255278b0a";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/debug/zipball/697c527acd9ea1b2d3efac34d9806bf255278b0a;
          sha256 = "00d4kbzswrymand3rrhyc173fs26x55d38bvs17d5y6bk5glr6q1";
        };
      };
    };
    "symfony/event-dispatcher" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-event-dispatcher-9b69aad7d4c086dc94ebade2d5eb9145da5dac8c";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/event-dispatcher/zipball/9b69aad7d4c086dc94ebade2d5eb9145da5dac8c;
          sha256 = "16zfkn3yw6nbkvc6sk5i7rp38hpda602499zvvys3l1hcx4cc2b2";
        };
      };
    };
    "symfony/filesystem" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-filesystem-b2da5009d9bacbd91d83486aa1f44c793a8c380d";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/filesystem/zipball/b2da5009d9bacbd91d83486aa1f44c793a8c380d;
          sha256 = "1ijgs2yj900q26f1dr81nbb1s3hjmhzh4pap13145r71acjh7q37";
        };
      };
    };
    "symfony/finder" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-finder-423746fc18ccf31f9abec43e4f078bb6e024b2d5";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/finder/zipball/423746fc18ccf31f9abec43e4f078bb6e024b2d5;
          sha256 = "15sifnvg12qsy9dmvl10f5ziygw0pkm1ws4v3bqqan1a1hdgg3d2";
        };
      };
    };
    "symfony/polyfill-mbstring" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-mbstring-3296adf6a6454a050679cde90f95350ad604b171";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/polyfill-mbstring/zipball/3296adf6a6454a050679cde90f95350ad604b171;
          sha256 = "02wyx9fjx9lyc5q5d3bnn8aw9xag8im2wqanmbkljwd5vmx9k9b2";
        };
      };
    };
    "symfony/process" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-process-ee2c91470ff262b1a00aec27875d38594aa87629";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/process/zipball/ee2c91470ff262b1a00aec27875d38594aa87629;
          sha256 = "1p4jz6fr71kd4bc9hpmdywvgdpjm3ryh8dkfjwrix71q5v5wzkd3";
        };
      };
    };
    "symfony/stopwatch" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-stopwatch-57021208ad9830f8f8390c1a9d7bb390f32be89e";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/stopwatch/zipball/57021208ad9830f8f8390c1a9d7bb390f32be89e;
          sha256 = "1x0sfv12wy8zg44xqkgii7p60vn3cab33zi0clcg8qpnx73qx40b";
        };
      };
    };
    "symfony/translation" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-translation-eee6c664853fd0576f21ae25725cfffeafe83f26";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/translation/zipball/eee6c664853fd0576f21ae25725cfffeafe83f26;
          sha256 = "1l6nxk7ik8a0hj9lrxgbzwi07xiwm9aai1yd4skswnb0r3qbbxzq";
        };
      };
    };
    "symfony/validator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-validator-1f7dbc5387c6cae22c44116c15638d6d9ac957d0";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/validator/zipball/1f7dbc5387c6cae22c44116c15638d6d9ac957d0;
          sha256 = "1rxqiz8y9i858a8bih31inl0rg0ma6k1pz1ja3ibfi48mj8wrcm6";
        };
      };
    };
    "twig/twig" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "twig-twig-b48680b6eb7d16b5025b9bfc4108d86f6b8af86f";
        src = fetchurl {
          url = https://api.github.com/repos/twigphp/Twig/zipball/b48680b6eb7d16b5025b9bfc4108d86f6b8af86f;
          sha256 = "1q82f246wq7whl11lx00n0skwmllppvpzg20x6q4frmw44dc6v9a";
        };
      };
    };
    "zendframework/zend-cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-cache-4983dff629956490c78b88adcc8ece4711d7d8a3";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-cache/zipball/4983dff629956490c78b88adcc8ece4711d7d8a3;
          sha256 = "0hl9lrhdpq4mxi7l8ca3xzmmkiyfr1iwgsr6fg6vwcppmbhnibk9";
        };
      };
    };
    "zendframework/zend-config" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-config-2920e877a9f6dca9fa8f6bd3b1ffc2e19bb1e30d";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-config/zipball/2920e877a9f6dca9fa8f6bd3b1ffc2e19bb1e30d;
          sha256 = "1gv5pcv7hclyk77sfc722w7qhxkgpz42wayj7nmqfjda0i6ka8fy";
        };
      };
    };
    "zendframework/zend-eventmanager" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-eventmanager-a5e2583a211f73604691586b8406ff7296a946dd";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-eventmanager/zipball/a5e2583a211f73604691586b8406ff7296a946dd;
          sha256 = "08a05gn40hfdy2zhz4gcd3r6q7m7zcaks5kpvb9dx1awgx0pzr8n";
        };
      };
    };
    "zendframework/zend-filter" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-filter-7b997dbe79459f1652deccc8786d7407fb66caa9";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-filter/zipball/7b997dbe79459f1652deccc8786d7407fb66caa9;
          sha256 = "1806q65kfjgn384l2a6ch2s7jy6xvdkh53r88990qp85cbk4755f";
        };
      };
    };
    "zendframework/zend-hydrator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-hydrator-22652e1661a5a10b3f564cf7824a2206cf5a4a65";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-hydrator/zipball/22652e1661a5a10b3f564cf7824a2206cf5a4a65;
          sha256 = "1wys4x4bw2i83h85wirl4b8l2pszzyr0d067mn6h7njipkqdn0dp";
        };
      };
    };
    "zendframework/zend-i18n" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-i18n-cfdb658121e0d7eb969a498c2f67f1eacaab9c63";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-i18n/zipball/cfdb658121e0d7eb969a498c2f67f1eacaab9c63;
          sha256 = "0fhsg1hngcdnvw2r9iq33z7pvcssqwx5a0zicx2slg3si0mqq4bd";
        };
      };
    };
    "zendframework/zend-json" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-json-4dd940e8e6f32f1d36ea6b0677ea57c540c7c19c";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-json/zipball/4dd940e8e6f32f1d36ea6b0677ea57c540c7c19c;
          sha256 = "09mmxcycy9vxykfv0lf2b7g0cspy1lmf01a1gmk98ny38knn7342";
        };
      };
    };
    "zendframework/zend-serializer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-serializer-7ac42b9a47e9cb23895173a3096bc3b3fb7ac580";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-serializer/zipball/7ac42b9a47e9cb23895173a3096bc3b3fb7ac580;
          sha256 = "0jpp5bbiisrhw6qppzmas46znmzkpyppgs2fz1xknl9qrvm43z9k";
        };
      };
    };
    "zendframework/zend-servicemanager" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-servicemanager-ba7069c94c9af93122be9fa31cddd37f7707d5b4";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-servicemanager/zipball/ba7069c94c9af93122be9fa31cddd37f7707d5b4;
          sha256 = "09ygbiwx8pmf55fg4682m4k07r3hhvkqb7gg7j2cn743xpi3126r";
        };
      };
    };
    "zendframework/zend-stdlib" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-stdlib-0e44eb46788f65e09e077eb7f44d2659143bcc1f";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-stdlib/zipball/0e44eb46788f65e09e077eb7f44d2659143bcc1f;
          sha256 = "0i4cds0qql22fj2bipkcpv9pc30s63h10gr15kh8k6jxd04ln2fn";
        };
      };
    };
    "zetacomponents/base" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zetacomponents-base-489e20235989ddc97fdd793af31ac803972454f1";
        src = fetchurl {
          url = https://api.github.com/repos/zetacomponents/Base/zipball/489e20235989ddc97fdd793af31ac803972454f1;
          sha256 = "0fwzbz6a47l0lmfw52rvmbd1fds06vdwjpmvgkivgqmzp8r87zl5";
        };
      };
    };
    "zetacomponents/document" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zetacomponents-document-688abfde573cf3fe0730f82538fbd7aa9fc95bc8";
        src = fetchurl {
          url = https://api.github.com/repos/zetacomponents/Document/zipball/688abfde573cf3fe0730f82538fbd7aa9fc95bc8;
          sha256 = "15bxwfcd934c41lw1ccmdypn4m1xq0p540x2bfcsc80m6d51nnll";
        };
      };
    };
  };
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "svanderburg-php-sbgallery";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "Apache-2.0";
  };
}