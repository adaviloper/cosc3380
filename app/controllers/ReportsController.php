<?php

	namespace App\Controllers;

	use Address;
	use App\Core\Auth;
	use Package;
	use PackageStatus;
	use State;
	use Transaction;
	use User;

	class ReportsController {

		public function getReports() {
			$user = Auth::user();
			if( $user->roleId == 2 || $user->roleId == 1 ) {
				$packageStatuses = PackageStatus::selectAll();

				return view( 'dashboard/reports' , compact( 'packageStatuses' ) );
			} else if( $user->roleId == 3 ) {
				return redirect( 'account' );
			} else if( $user->roleId == 1 ) {
				return redirect( 'admin' );
			}

			return redirect( 'login' );
		}

		public function showReports() {
			$user = Auth::user();
			if( $user ) {
				if( $user->roleId == 2 || $user->roleId == 1 ) {
					$packageStatuses = PackageStatus::selectAll();
					$cols            = [];
					$ops             = [];
					$vals            = [];
					if( $user->roleId === 2 ) {
						$cols = [ 'postOfficeId' ];
						$ops  = [ '=' ];
						$vals = [ $user->roleId ];
					}
					if( isset( $_POST[ 'reportOption' ] ) ) {
						if( $_POST[ 'reportOption' ] == 'queryPackages' ) {
							if( isset( $_POST[ 'postOfficeSelector' ] ) ) {
								$cols[] = 'postOfficeId';
								$ops[]  = '=';
								$vals[] = $_POST[ 'postOfficeSelector' ];
							}
							if( isset( $_POST[ 'packageStatusSelector' ] ) ) {
								$cols[] = 'packageStatus';
								$ops[]  = '=';
								$vals[] = $_POST[ 'packageStatusSelector' ];
							}
							if( isset( $_POST[ 'startDate' ] ) && $_POST[ 'startDate' ] !== '' ) {
								$cols[] = 'createdAt';
								$ops[]  = '>=';
								$vals[] = date( "Y-m-d H:i:s" , strtotime( $_POST[ 'startDate' ] ) );
							}
							if( isset( $_POST[ 'endDate' ] ) && $_POST[ 'endDate' ] !== '' ) {
								$cols[] = 'createdAt';
								$ops[]  = '<=';
								$vals[] = date( "Y-m-d H:i:s" , strtotime( $_POST[ 'endDate' ] ) );
							}
							$packages = Package::findAll()
							                   ->where( $cols , $ops , $vals )
							                   ->orderBy( 'createdAt' , 'DESC' )
							                   ->get();
							foreach( $packages as $package ) {
								$package->destination          = Address::find()
								                                        ->where( [ 'id' ] , [ '=' ] ,
								                                                 [ $package->destinationId ] )
								                                        ->get();
								$package->destination->state   = State::find()
								                                      ->where( [ 'id' ] , [ '=' ] ,
								                                               [ $package->destination->stateId ] )
								                                      ->get();
								$package->returnAddress        = Address::find()
								                                        ->where( [ 'id' ] , [ '=' ] ,
								                                                 [ $package->returnAddressId ] )
								                                        ->get();
								$package->returnAddress->state = State::find()
								                                      ->where( [ 'id' ] , [ '=' ] ,
								                                               [ $package->returnAddress->stateId ] )
								                                      ->get();
								$package->status               = PackageStatus::find()
								                                              ->where( [ 'id' ] , [ '=' ] ,
								                                                       [ $package->packageStatus ] )
								                                              ->get();

								$package->user = User::find()
								                     ->where( [ 'id' ] , [ '=' ] , [ $package->userId ] );
							}
							// dd( $_POST , $cols , $ops , $vals );
							return view( 'dashboard/reports' , compact( 'packageStatuses' , 'packages' ) );
						} else {
							$transactions = Transaction::findAll()
							                           ->where( $cols , $ops , $vals )
							                           ->orderBy( 'createdAt' , 'DESC' )
							                           ->get();
							foreach( $transactions as $transaction ) {
								$transaction->customer = User::find()
								                             ->where( [ 'id' ] , [ '=' ] ,
								                                      [ $transaction->customerId ] )
								                             ->get();
							}
							// dd( $_POST , $cols , $ops , $vals, $transactions );
							return view( 'dashboard/reports' , compact( 'packageStatuses' , 'transactions' ) );
						}
					}


				} else if( $user->roleId == 3 ) {
					return redirect( 'account' );
				} else if( $user->roleId == 1 ) {
					return redirect( 'admin' );
				}
			}

			return redirect( 'login' );
		}
	}