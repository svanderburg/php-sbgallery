{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "svanderburg/php-sbcrud" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbcrud-f2f6f5bac0cb3796667fa1e57ddc71321238806b";
        url = "https://github.com/svanderburg/php-sbcrud.git";
        rev = "f2f6f5bac0cb3796667fa1e57ddc71321238806b";
        sha256 = "1scsm3765ggga7ql5bgcsg32qc39xcka4fgdhq8bc09mkp0jvq77";
      };
    };
    "svanderburg/php-sbdata" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbdata-0f6422e13778bbb58fc95b75d3dc3f1ae972613d";
        url = "https://github.com/svanderburg/php-sbdata.git";
        rev = "0f6422e13778bbb58fc95b75d3dc3f1ae972613d";
        sha256 = "13jmh9qwqidrqlknniibbkmwn92rrbqbrajqmf4lyb131bl78b0q";
      };
    };
    "svanderburg/php-sbeditor" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sbeditor-fa0d7517d81b43ac5d980d15132967c976efb588";
        url = "https://github.com/svanderburg/php-sbeditor.git";
        rev = "fa0d7517d81b43ac5d980d15132967c976efb588";
        sha256 = "0xqxkaz78vrffxlivd1lp8c5h46j07w6zp6alm9pn9xj374q1vd2";
      };
    };
    "svanderburg/php-sblayout" = {
      targetDir = "";
      src = fetchgit {
        name = "svanderburg-php-sblayout-32894d6b1f8280a97a0878d0615454dfbf77efc9";
        url = "https://github.com/svanderburg/php-sblayout.git";
        rev = "32894d6b1f8280a97a0878d0615454dfbf77efc9";
        sha256 = "1ia2mnkx2p6sfxgqfs4faz8ccajni5iax3hi8rxnk1ckxpkyl74p";
      };
    };
  };
  devPackages = {};
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "svanderburg-php-sbgallery";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    license = "Apache-2.0";
  };
}