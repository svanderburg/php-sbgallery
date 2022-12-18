{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-25a22f640f11276b758efc5609027545576b659c";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "25a22f640f11276b758efc5609027545576b659c";
        sha256 = "15a2q7hghabh07b5hjj32sw6x2q4md0j8svc4fmcrvvw31ck66wn";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-0a9c962dcb92dfade5015c43637b8ab8800f52c3";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "0a9c962dcb92dfade5015c43637b8ab8800f52c3";
        sha256 = "0v5s2w9l8dcxk70m8v3mqad2505i7h9xlzb659p1b9csjyr6d21v";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-07e246b65c7b2ea37ad3b8a7d1355974733c1102";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "07e246b65c7b2ea37ad3b8a7d1355974733c1102";
        sha256 = "1sddxdnw66s464a0n49chgdnpfaivbzxp5qd99x91gxic1cfi07s";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-97546d499170396598338a62bc2043fb84ef24c1";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "97546d499170396598338a62bc2043fb84ef24c1";
        sha256 = "0qzh5yznqndalsd91pc1rdgla4a59y7ga72xcwnl1q4gyl83wmlg";
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
