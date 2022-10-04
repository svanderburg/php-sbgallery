{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-baec489db8994b7bb1863014121fa42839ec8a56";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "baec489db8994b7bb1863014121fa42839ec8a56";
        sha256 = "126cms2i21d66pv6vw50w9anrpdbxw057xar000i9jmm7y13h8di";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-a762a1e7e85e7c10b82ecf682694e9fccf37c033";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "a762a1e7e85e7c10b82ecf682694e9fccf37c033";
        sha256 = "0rbhslb717lv845qnhq6wfb2i9z5d3x286hhci7f2aw7n3barfjy";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-81dd24557596513132ab236a50096c424ba8a838";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "81dd24557596513132ab236a50096c424ba8a838";
        sha256 = "095p528i4kjm95fd69a8vx5ghd5qsyvzslsgcbhv7316dda9xa54";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-fcbba66c36af3ab258b50dc5f396b82d2170851c";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "fcbba66c36af3ab258b50dc5f396b82d2170851c";
        sha256 = "0wvjcgsx2g7c25krk8hckdgflczps7qsnw6wqj22glq7065c10d0";
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
