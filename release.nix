{ nixpkgs ? <nixpkgs>
, systems ? [ "x86_64-linux" ]
}:

let
  pkgs = import nixpkgs {};
in
{
  package = pkgs.lib.genAttrs systems (system: (import ./default.nix {
    inherit pkgs system;
    noDev = true;
  }));

  dev = pkgs.lib.genAttrs systems (system: (import ./default.nix {
    inherit pkgs system;
  }).override {
    buildInputs = [ pkgs.doxygen ];
    executable = true;
    postInstall = ''
      doxygen
      mv doc $out
      mkdir -p $out/nix-support
      echo "doc api $out/share/doc/html" >> $out/nix-support/hydra-build-products
    '';
  });
}
