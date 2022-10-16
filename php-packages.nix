{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-a294aec4e3196aa79940128e42980f99e84c13dd";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "a294aec4e3196aa79940128e42980f99e84c13dd";
        sha256 = "0c23rfxjzacfjsz5fl5xwmv21qrb5r1v0ghqyzf9js469a3gpmzh";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-125b3f9cdc540959c6b2b2a715b6b4f298369314";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "125b3f9cdc540959c6b2b2a715b6b4f298369314";
        sha256 = "0q9zjybhij7rz5wq4gm40py088yypry82zm5qk5qv1df3l416yg5";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-e5ea1cde3e440284b1411c5a7eeb96cec5bc1ab4";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "e5ea1cde3e440284b1411c5a7eeb96cec5bc1ab4";
        sha256 = "06pzas0qhmfpyb8lqy1n0vhy3fs51w0kd1drl7ky3wljdln1vpql";
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
