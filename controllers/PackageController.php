<?php

	namespace App\Controllers;

	use Address;
	use App\Core\App;
	use App\Core\Auth;
	use Package;
	use PackageStatus;
	use State;
	use User;

	class PackageController {

		public function postOfficeInventory() {
			$user = Auth::user();
			if( $user ) {
				$packages = Package::findAll()
				                   ->where( [ 'postOfficeId' ] ,
				                            [ '=' ] ,
				                            [ $user->postOfficeId ] )
				                   ->get();

				foreach( $packages as $package ) {
					$package->user                 = User::find()
					                                     ->where( [ 'id' ] ,
					                                              [ '=' ] ,
					                                              [ $package->userId ] )
					                                     ->get();
					$package->destination          = Address::find()
					                                        ->where( [ 'id' ] ,
					                                                 [ '=' ] ,
					                                                 [ $package->destinationId ] )
					                                        ->get();
					$package->destination->state   = State::find()
					                                      ->where( [ 'id' ] ,
					                                               [ '=' ] ,
					                                               [ $package->destination->stateId ] )
					                                      ->get();
					$package->returnAddress        = Address::find()
					                                        ->where( [ 'id' ] ,
					                                                 [ '=' ] ,
					                                                 [ $package->returnAddressId ] )
					                                        ->get();
					$package->returnAddress->state = State::find()
					                                      ->where( [ 'id' ] ,
					                                               [ '=' ] ,
					                                               [ $package->returnAddress->stateId ] )
					                                      ->get();
					$package->status               = PackageStatus::find()
					                                              ->where( [ 'id' ] ,
					                                                       [ '=' ] ,
					                                                       [ $package->packageStatus ] )
					                                              ->get();
				}

				return view( 'dashboard/packages' ,
				             compact( 'packages' ) );
			}

			return redirect( 'login' );
		}

		public function editPackage( $packageId ) {
			$package                       = Package::find()
			                                        ->where( [ 'id' ] ,
			                                                 [ '=' ] ,
			                                                 [ $packageId ] )
			                                        ->get();
			$package->user                 = User::find()
			                                     ->where( [ 'id' ] ,
			                                              [ '=' ] ,
			                                              [ $package->userId ] )
			                                     ->get();
			$package->destination          = Address::find()
			                                        ->where( [ 'id' ] ,
			                                                 [ '=' ] ,
			                                                 [ $package->destinationId ] )
			                                        ->get();
			$package->destination->state   = State::find()
			                                      ->where( [ 'id' ] ,
			                                               [ '=' ] ,
			                                               [ $package->destination->stateId ] )
			                                      ->get()->state;
			$package->returnAddress        = Address::find()
			                                        ->where( [ 'id' ] ,
			                                                 [ '=' ] ,
			                                                 [ $package->returnAddressId ] )
			                                        ->get();
			$package->returnAddress->state = State::find()
			                                      ->where( [ 'id' ] ,
			                                               [ '=' ] ,
			                                               [ $package->returnAddress->stateId ] )
			                                      ->get()->state;
			$package->status               = PackageStatus::find()
			                                              ->where( [ 'id' ] ,
			                                                       [ '=' ] ,
			                                                       [ $package->packageStatus ] )
			                                              ->get();

			return view( 'dashboard/editPackage' ,
			             compact( 'package' ) );
		}

		public function updatePackage( $packageId ) {
			$package = Package::find()
			                  ->where( [ 'id' ] ,
			                           [ '=' ] ,
			                           $packageId )
			                  ->get();

			Address::update( [
				'street' => $_POST[ 'destinationAddressStreet' ],
				'city' => $_POST[ 'destinationAddressCity' ],
				'zipCode' => $_POST[ 'destinationAddressZipCode' ]
			                 ] )
			       ->where( [ 'id' ] ,
			                [ '=' ] ,
			                $package->destinationId )
			       ->get();

			$address = Address::find()
			                  ->where( [ 'id' ] ,
			                           [ '=' ] ,
			                           $package->destinationId )
			                  ->get();

			// dd($address);
			redirect( "packages/{$packageId}" );
		}

		public function show() {
			$packages = Package::selectAll();

			foreach( $packages as $package ) {
				$package->user          = User::find()
				                              ->where( [ 'id' ] ,
				                                       [ '=' ] ,
				                                       [ $package->userId ] )
				                              ->get();
				$package->destination   = Address::find()
				                                 ->where( [ 'id' ] ,
				                                          [ '=' ] ,
				                                          [ $package->destinationId ] )
				                                 ->get();
				$package->returnAddress = Address::find()
				                                 ->where( [ 'id' ] ,
				                                          [ '=' ] ,
				                                          [ $package->returnAddressId ] )
				                                 ->get();
			}

			return view( 'packages/packages' ,
			             compact( 'packages' ) );
		}

		public function packageDetail( $packageId ) {
			$package         = Package::find()
			                          ->where( [ 'id' ] ,
			                                   [ '=' ] ,
			                                   [ $packageId ] )
			                          ->get();
			$package->status = PackageStatus::find()
			                                ->where( [ 'id' ] ,
			                                         [ '=' ] ,
			                                         [ $package->packageStatus ] )
			                                ->get();

			$package->user                 = User::find()
			                                     ->where( [ 'id' ] ,
			                                              [ '=' ] ,
			                                              [ $package->userId ] )
			                                     ->get();
			$package->destination          = Address::find()
			                                        ->where( [ 'id' ] ,
			                                                 [ '=' ] ,
			                                                 [ $package->destinationId ] )
			                                        ->get();
			$package->destination->state   = State::find()
			                                      ->where( [ 'id' ] ,
			                                               [ '=' ] ,
			                                               [ $package->destination->stateId ] )
			                                      ->get()->state;
			$package->returnAddress        = Address::find()
			                                        ->where( [ 'id' ] ,
			                                                 [ '=' ] ,
			                                                 [ $package->returnAddressId ] )
			                                        ->get();
			$package->returnAddress->state = State::find()
			                                      ->where( [ 'id' ] ,
			                                               [ '=' ] ,
			                                               [ $package->returnAddress->stateId ] )
			                                      ->get()->state;

			return view( 'dashboard/packageDetail' ,
			             compact( 'package' ) );
		}
	}