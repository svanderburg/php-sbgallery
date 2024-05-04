{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-ac22af4b4d09d1ea7b0e91bfae27f5ab57b17937";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "ac22af4b4d09d1ea7b0e91bfae27f5ab57b17937";
        sha256 = "1k6yxdwx9n9shhf4nwds9msmac80r8dv8pgs8770mxrnj8h8bvkq";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-434133b7b1341885bd064ba84ee48732abeb9b3d";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "434133b7b1341885bd064ba84ee48732abeb9b3d";
        sha256 = "1m753gy4dicajxhrxvvkxbb6pchdppbkwvwbf4z9dzlxnwxsij00";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-6ca4ac4c24324da03d7b5cabbbc74e4a128ec0b4";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "6ca4ac4c24324da03d7b5cabbbc74e4a128ec0b4";
        sha256 = "0ymraavhjf6033s7rsbx4sy69g2g45fjxl484awr47ycxfalyfqz";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-126b3eb89e1e5a90675c26746be560c712300698";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "126b3eb89e1e5a90675c26746be560c712300698";
        sha256 = "0mgy3fdfm729mjgipbpfr2w8j6fm1ylqyglf350aivq57ccyhg3h";
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
