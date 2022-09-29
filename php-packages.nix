{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-7d51bbb5d987a679cc6e19cae5e5a307529fa9f9";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "7d51bbb5d987a679cc6e19cae5e5a307529fa9f9";
        sha256 = "097zchacx5qh4bklbh8491q7a0y0ywq2w5qhd3kxnzcxk7cr65lc";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-669665068a1b1ad00be2dd6d6234bddd5f2bec4f";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "669665068a1b1ad00be2dd6d6234bddd5f2bec4f";
        sha256 = "0ijryzwk0kdic3vzky84sbw35s9imakvd54v026qql3q5pm8s2q9";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-e3b8780c230257e9b1c931e4289a854666ae9614";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "e3b8780c230257e9b1c931e4289a854666ae9614";
        sha256 = "0969bb89fvfrws5yx3lwvnmnjhq39kxz9nj7nlm5gn80kqggcbii";
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
