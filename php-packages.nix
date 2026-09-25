{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-de35a663df9ff89024ce6ae8f3b35ab32d62524b";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "de35a663df9ff89024ce6ae8f3b35ab32d62524b";
        sha256 = "0vrpjn946x04yq3aw18xachzvs0hpy18907l0n19jnfw8xb9knvp";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-e348b06cba899322d1371384c532413890596f91";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "e348b06cba899322d1371384c532413890596f91";
        sha256 = "1d63dyp7mbdps1sp0wbkd0acs00id6cfayv69bckvhdwkhl9vsi5";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-3c8b2383663d8c2294d033065f752cfa683d3095";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "3c8b2383663d8c2294d033065f752cfa683d3095";
        sha256 = "0zqxn83klgg87m125mdn1z6cpm1v9j10xml24qgqyng76d88ahx8";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-7ee4794bedd8d1ea10fc90a9b561978186a7a6b7";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "7ee4794bedd8d1ea10fc90a9b561978186a7a6b7";
        sha256 = "04fmndssrxnahm6gz09qf10nwxw4kcpnfr09m4z0g9l3kh4dwxyh";
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
