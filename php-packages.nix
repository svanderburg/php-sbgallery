{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-e28e621b0e01c8adea385ac18ceaa3ee68e67d54";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "e28e621b0e01c8adea385ac18ceaa3ee68e67d54";
        sha256 = "1czrmr4rvgsda2gjn12pixbxixnfd0mp41904h77z67s1q1qj3hk";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-e316850043401eb9f6edb64df4b1fb63db690691";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "e316850043401eb9f6edb64df4b1fb63db690691";
        sha256 = "1b96k0xknvk2mdl303h8q41dkr6adbl7plipx81qwbhhg3wzpzgp";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-50d8eb0c2a34b432804ba73294e90381d4e187e4";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "50d8eb0c2a34b432804ba73294e90381d4e187e4";
        sha256 = "1qizmaqjasmqsyrdhyzwzwvxlgh4pnk6kq1h85kr2lsliai93b4g";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-7b56951ac05dc73e57499c68680dd140d944e711";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "7b56951ac05dc73e57499c68680dd140d944e711";
        sha256 = "0wh554fdnnkb73xn798nj2yn73yr4f456hp9bbgi238gmaw5xwns";
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
