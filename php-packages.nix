{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-336f427aaf2ac989ede3d0ce4c62d2a572d63ae9";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "336f427aaf2ac989ede3d0ce4c62d2a572d63ae9";
        sha256 = "0aq1agln96cqi3j51q2lmqpqm2hsw6218wrfpqngsfxw5l8zk35v";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-abaed513ead847b98696797467bc8c8422a64d17";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "abaed513ead847b98696797467bc8c8422a64d17";
        sha256 = "0f7m5ma3ffhybgq6h2zclmylhf5lmnhizgk5z8ip3cp4cigkcl2c";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-2d12764ff07d415f71f602eb0e08db2136ebf2d8";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "2d12764ff07d415f71f602eb0e08db2136ebf2d8";
        sha256 = "1d4ymwx6v629z39f69z6lfl13m1z88m8sykx0njy7h80ywl5yzyw";
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
