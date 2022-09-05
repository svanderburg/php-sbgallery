{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-09d15dd986a1739a35f9290a280af23b4e1d7b03";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "09d15dd986a1739a35f9290a280af23b4e1d7b03";
        sha256 = "06yp73sycf8yqqq4d8rbv7qvy8dj80llqzxz65cqpb7wkaj18pjl";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-2532ede5954d15690cf6e00b6280a3ecd01aff4e";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "2532ede5954d15690cf6e00b6280a3ecd01aff4e";
        sha256 = "0l30w61pvd3c42l28g81z3ninj5hcv8q8kci8yaw57bkgvwfr447";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-ff082a75714cd7fe3468479806e23b43cb7c7c68";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "ff082a75714cd7fe3468479806e23b43cb7c7c68";
        sha256 = "0hkncp2fs5zax0kl0dxl3b9smb41ngb0fpv3jndq1c4p5ayf8a02";
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
