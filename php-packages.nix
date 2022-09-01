{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-4590ff44ee77acd579ea246f8925836000b852c1";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "4590ff44ee77acd579ea246f8925836000b852c1";
        sha256 = "00lh3bzhqdlavy9jj07rfgyrzjqbwx4bdxbxmqdr65rqq5bmm0d7";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-72a189a2d2d6e220b518654dba2009f3e20f5daa";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "72a189a2d2d6e220b518654dba2009f3e20f5daa";
        sha256 = "1x9x1hd1p6c3iq39kwwcgjxjsp9hnwplql80m67kim3rs2y73pjb";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-d4af4608337e9d44108e084675dfef6a24ad0bc0";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "d4af4608337e9d44108e084675dfef6a24ad0bc0";
        sha256 = "1sqxgdv9sll49dsrf9cin1wlf8zfwha54crijimfnbqs12lx1i34";
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
