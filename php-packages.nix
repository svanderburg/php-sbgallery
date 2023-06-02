{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-7a5162f12847dd5b1e96703668543d2b7f1b5024";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "7a5162f12847dd5b1e96703668543d2b7f1b5024";
        sha256 = "0562r9zy3lxm99b1dw2npski6j4b3992g5ziqm4vacxx45vgdqc7";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-f61559eaca14e0795960e3c5cb42a2fec1c5efdd";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "f61559eaca14e0795960e3c5cb42a2fec1c5efdd";
        sha256 = "1krdcz4kzxfq2lxzwmrrly5k918gnvj0qbvj0dwndglr4l1hr7z0";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-56747f6ac3aa4244ffb21f8ca9c2011990a3482c";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "56747f6ac3aa4244ffb21f8ca9c2011990a3482c";
        sha256 = "19hxbvlwnly3xqy5w7zps73dsjjfxg006lsv94ax45fss5ax1ykm";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-56b8668eae093d47c9ab8f6fab0522524cb0a89e";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "56b8668eae093d47c9ab8f6fab0522524cb0a89e";
        sha256 = "0c32hfayb70r5nq3bz38vqkqgy39qcn116vydvx37dkgvqnalvjq";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "svanderburg-php-sbgallery";
  src = composerEnv.filterSrc ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "Apache-2.0";
  };
}
