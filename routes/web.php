<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\InterpriseController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\FinancialAccountsController;
use App\Http\Controllers\BankSlipController;
use App\Http\Controllers\JuridicalController;
use App\Http\Controllers\ActiveController;
use App\Http\Controllers\ReportsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::prefix('/')->group(function(){
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    Route::get('/allUsers', [UsersController::class,'index'])->name('allUsers');
    Route::get('/myProfile', [UsersController::class,'myProfile'])->name('myProfile');
    Route::any('/addUser', [UsersController::class,'add'])->name('addUser');
    Route::get('/editUser/{idUser}', [UsersController::class,'edit'])->name('editUser');
    Route::post('/editAction', [UsersController::class,'editAction'])->name('editAction');
    Route::get('/deleteUser/{idUser}',[UsersController::class,'delete'])->name('deleteUser');
    Route::get('/firstAccess/{idUser}',[UsersController::class,'firstAccess'])->name('firstAccess');

    Route::get('/allCompanies',[CompaniesController::class,'index'])->name('allCompanies');
    Route::any('/addCompany', [CompaniesController::class,'add'])->name('addCompany');
    Route::any('/editCompany/{idCompany}', [CompaniesController::class,'edit'])->name('editCompany');
    Route::get('/suspendCompany/{idCompany}', [CompaniesController::class,'suspend'])->name('suspend');


    Route::get('/allInterprises',[InterpriseController::class,'index'])->name('allInterprises');
    Route::any('/addInterprises',[InterpriseController::class,'add'])->name('addInterprise');
    Route::any('/editInterprises/{idInterprise}',[InterpriseController::class,'edit'])->name('editInterprise');
    Route::get('/deleteInterprises/{idInterprise}',[InterpriseController::class,'delete'])->name('deleteInterprise');
    
    Route::get('/allLot/{idInterprise}',[InterpriseController::class,'allLot'])->name('allLot');
    Route::any('/addLot/{idInterprise}',[InterpriseController::class,'addLot'])->name('addLot');
    Route::any('/editLot/{idInterprise}',[InterpriseController::class,'editLot'])->name('editLot');
    Route::get('/deleteLot/{idInterprise}',[InterpriseController::class,'deleteLot'])->name('deleteLot');
    Route::get('/seeLot/{idInterprise}',[InterpriseController::class,'seeLot'])->name('seeLot');
    Route::post('/getLot',[AjaxController::class,'getLot'])->name('getLot');

    Route::get('/allClients',[ClientsController::class,'index'])->name('allClients');
    Route::get('/allClientsSeveral',[ClientsController::class,'allClientsSeveral'])->name('allClientsSeveral');

    Route::any('/addClient',[ClientsController::class,'add'])->name('addClient');
    Route::any('/addClient/{cpf_representative?}/{edit_idClient?}',[ClientsController::class,'add'])->name('addClientRepresent');
    Route::any('/editClient/{idClient}',[ClientsController::class,'edit'])->name('editClient');
    Route::get('/deleteClient/{idClient}',[ClientsController::class,'delete'])->name('deleteClient');
    Route::get('/seeClient/{idClient}',[ClientsController::class,'seeClient'])->name('seeClient');
    
    Route::post('/getAddress',[AjaxController::class,'getAddressByCep'])->name('getAddressByCep');
    Route::post('/getCnpj',[AjaxController::class,'getAllInfoByCnpj'])->name('getAllInfoByCnpj');
    Route::post('/getCpf',[AjaxController::class,'verifyClientExist'])->name('verifyClientExist');
    Route::post('/getAllClients',[AjaxController::class,'getAllClients'])->name('getAllClients');
    Route::post('/getBankSlip',[AjaxController::class,'getBankSlip'])->name('getBankSlip');
    Route::post('/contract_sale_html',[AjaxController::class,'contractSaleHtml'])->name('contractSaleHtml');

    Route::get('/allSales',[SalesController::class,'index'])->name('allSales');
    Route::any('/addSale',[SalesController::class,'add'])->name('addSale');
    Route::post('/updateSale',[SalesController::class,'updateSale'])->name('updateSale');
    Route::post('/verifyContract',[AjaxController::class,'verifyContractNumber'])->name('verifyContractNumber');
    Route::get('/seeSale/{idSale}/{idJuridical?}',[SalesController::class,'seeSale'])->name('seeSale');
    Route::post('/finishContractSale',[SalesController::class,'finishContractSale'])->name('finishContractSale');
    Route::post('/almostFinishSale',[SalesController::class,'almostFinishSale'])->name('almostFinishSale');
    Route::post('/finishSale',[SalesController::class,'finishSale'])->name('finishSale');
    Route::get('/suspendSale/{idSale}',[SalesController::class,'suspendSale'])->name('suspendSale');
    Route::get('/allParcels',[SalesController::class,'allParcelsView'])->name('allParcels');
    Route::post('/payParcel',[SalesController::class,'payParcel'])->name('payParcel');


    Route::any('/addContact/{idSale}/{type}/{allContact?}',[ContactController::class,'addView'])->name('addView');
    Route::post('/addSeveralContact',[ContactController::class,'addSeveralContact'])->name('addSeveralContact');
    Route::post('/severalSolution',[ContactController::class,'severalSolution'])->name('severalSolution');
    Route::post('/forcePayParcelsRate',[ContactController::class,'forcePayParcelsRate'])->name('forcePayParcelsRate');
    Route::post('/addChangeOwnerContact',[ContactController::class,'addChangeOwnerContact'])->name('addChangeOwnerContact');
    Route::post('/changeOwner',[ContactController::class,'changeOwner'])->name('changeOwner');
    Route::post('/addChangeExpiredDate',[ContactController::class,'addChangeExpiredDate'])->name('addChangeExpiredDate');
    Route::get('/changeExpiredDaySale/{idContact}',[ContactController::class,'changeExpiredDaySale'])->name('changeExpiredDaySale');
    Route::post('/addRefinancingContact',[ContactController::class,'addRefinancingContact'])->name('addRefinancingContact');
    Route::get('/refinancingSuccess/{idSale}/{idContact}/{forceConfirm?}',[ContactController::class,'refinancingSuccess'])->name('refinancingSuccess');
    Route::post('/addCancelContact',[ContactController::class,'addCancelContact'])->name('addCancelContact');
    Route::post('/addReissueContact',[ContactController::class,'addReissueContact'])->name('addReissueContact');
    Route::post('/addChangeLotContact',[ContactController::class,'addChangeLotContact'])->name('addChangeLotContact');
    Route::post('/changeLotSuccess',[ContactController::class,'changeLotSuccess'])->name('changeLotSuccess');
    
    Route::post('/reissueContact',[ContactController::class,'reissueContact'])->name('reissueContact');
    Route::get('/seeContact/{idContact}',[ContactController::class,'seeContact'])->name('seeContact');
    Route::get('/updateContact/{idContact}/{idSale}/{status}',[ContactController::class,'updateStatus'])->name('updateContact');
    Route::post('/updateContactFile',[ContactController::class,'updateContactFile'])->name('updateContactFile');
    Route::post('/cancelSale',[ContactController::class,'cancelSale'])->name('cancelSale');
    Route::get('/allContact',[ContactController::class,'index'])->name('allContact');
    Route::get('/doneContact/{idContact}',[ContactController::class,'doneContact'])->name('doneContact');
    
    
    Route::get('/index',[IndexController::class,'index'])->name('index');
    Route::post('/addIndex',[IndexController::class,'add'])->name('addIndex');
    Route::post('/editIndex',[IndexController::class,'edit'])->name('editIndex');
    Route::get('/deleteIndex/{idIndex}',[IndexController::class,'delete'])->name('deleteIndex');
    Route::get('/seeIndexValue/{idIndex}',[IndexController::class,'seeIndexValue'])->name('seeIndexValue');
    Route::get('/seeAllIndex',[IndexController::class,'seeAllIndexValue'])->name('seeAllIndexValue');
    Route::post('/addIndexValue',[IndexController::class,'addIndexValue'])->name('addIndexValue');
    Route::post('/addIndexValueNotification',[IndexController::class,'addIndexValueNotification'])
        ->name('addIndexValueNotification');
    Route::post('/editIndexValue',[IndexController::class,'editIndexValue'])->name('editIndexValue');
    Route::get('/deleteIndexValue/{idIndexValue}/{idIndex}/{allIndexVal?}',[IndexController::class,'deleteIndexValue'])->name('deleteIndexValue');
    
    Route::get('/financialAccounts',[FinancialAccountsController::class,'index'])->name('allFinancialAccounts');
    Route::get('/seefinancialAccounts/{idAccount}',[FinancialAccountsController::class,'seefinancialAccounts'])->name('seefinancialAccounts');
    Route::any('/addFinancialAccounts',[FinancialAccountsController::class,'addFinancialAccounts'])->name('addFinancialAccounts');
    Route::any('/editFinancialAccounts/{idAccount}',[FinancialAccountsController::class,'editFinancialAccounts'])->name('editFinancialAccounts');
    Route::get('/deleteAccounts/{idAccount}',[FinancialAccountsController::class,'deleteAccounts'])->name('deleteAccounts');

    Route::get('/edit_contract_sale/{id_sale}',[ContractsController::class,'contractEditSale'])->name('contractEditSale');
    Route::get('/contract_sale/{id_sale}/{edit?}',[ContractsController::class,'contractSale'])->name('contractSale');
    Route::get('/contract_cancel/{id_contact}',[ContractsController::class,'contractCancel'])->name('contractCancel');
    Route::get('/contract_change_owner/{id_contact}',[ContractsController::class,'contractChangeOwner'])->name('contractChangeOwner');
    Route::get('/contract_change_lot/{id_contact}',[ContractsController::class,'contractChangeLot'])->name('contractChangeLot');
    
    Route::get('/sendBankSlip',[BankSlipController::class,'index'])->name('sendBankSlip');
    Route::get('/pending_bankSlip/{id_financial}',[BankSlipController::class,'pending_bankSlip'])->name('pending_bankSlip');
    Route::get('/addBankSlip',[BankSlipController::class,'addBankSlip'])->name('addBankSlip');
    Route::post('/generateBankSlip',[BankSlipController::class,'generateBankSlip'])->name('generateBankSlip');
    Route::post('/generateSendBankSlip',[BankSlipController::class,'generateSendBankSlip'])->name('generateSendBankSlip');
    Route::get('/returnBankSlip',[BankSlipController::class,'returnBankSlip'])->name('returnBankSlip');
    Route::get('/returnBankSlipInfo/{id_bankSlipReturn}',[BankSlipController::class,'returnBankSlipInfo'])->name('returnBankSlipInfo');
    Route::post('/addReturnBankSlipFile',[BankSlipController::class,'addReturnBankSlipFile'])->name('addReturnBankSlipFile');

    Route::get('/accessDenied',[VerificationController::class,'accessDenied'])->name('accessDenied');

    Route::get('/allJuridicalContacts',[JuridicalController::class,'index'])->name('allJuridicalContacts');
    Route::post('/addJuridicalContact',[JuridicalController::class,'addJuridicalContact'])->name('addJuridicalContact');
    Route::get('/seeJuridicalContact/{idJuridical}',[JuridicalController::class,'seeJuridical'])->name('seeJuridicalContact');
    Route::post('/updateResolutionJuridical',[JuridicalController::class,'updateResolutionJuridical'])->name('updateResolutionJuridical');
    Route::post('/finalResolutionJuridical',[JuridicalController::class,'finalResolutionJuridical'])->name('finalResolutionJuridical');
    Route::post('/startJudicialProcess',[JuridicalController::class,'startJudicialProcess'])->name('startJudicialProcess');
    Route::post('/addJuridicalUpdate',[JuridicalController::class,'addJuridicalUpdate'])->name('addJuridicalUpdate');
    
    Route::get('/allTransfersBank',[ActiveController::class,'allTransfersBank'])->name('allTransfersBank');
    Route::get('/addTransfersBankView',[ActiveController::class,'addTransfersBankView'])->name('addTransfersBankView');
    Route::get('/updateTransfersBankView/{idTransferBank}',[ActiveController::class,'updateTransfersBankView'])->name('updateTransfersBankView');
    Route::post('/updateTransfersBank',[ActiveController::class,'updateTransfersBank'])->name('updateTransfersBank');
    Route::post('/addTransfersBank',[ActiveController::class,'addTransfersBank'])->name('addTransfersBank');
    Route::get('/deleteTransferBank/{idTransferBank}',[ActiveController::class,'deleteTransferBank'])->name('deleteTransferBank');
    
    Route::get('/allBanks',[ActiveController::class,'allBanks'])->name('allBanks');
    Route::post('/addBank',[ActiveController::class,'addBank'])->name('addBank');
    Route::post('/updateBank',[ActiveController::class,'updateBank'])->name('updateBank');
    Route::get('/deleteBank/{idBank}',[ActiveController::class,'deleteBank'])->name('deleteBank');
    
    Route::get('/allInternalAccounts',[ActiveController::class,'allInternalAccounts'])->name('allInternalAccounts');
    Route::post('/addInternalAccount',[ActiveController::class,'addInternalAccount'])->name('addInternalAccount');
    Route::post('/updateInternalAccount',[ActiveController::class,'updateInternalAccount'])->name('updateInternalAccount');
    Route::get('/deleteInternalAccount/{idInternalAccount}',[ActiveController::class,'deleteInternalAccount'])->name('deleteInternalAccount');
    
    Route::get('/allProgramedPayment',[ActiveController::class,'allProgramedPayment'])->name('allProgramedPayment');
    Route::get('/addProgramedPaymentView',[ActiveController::class,'addProgramedPaymentView'])->name('addProgramedPaymentView');
    Route::post('/addProgramedPayment',[ActiveController::class,'addProgramedPayment'])->name('addProgramedPayment');
    Route::get('/deleteProgramedPayment/{idProgramedPayment}',[ActiveController::class,'deleteProgramedPayment'])->name('deleteProgramedPayment');
    Route::get('/seeProgramedPayment/{idProgramedPayment}',[ActiveController::class,'seeProgramedPayment'])->name('seeProgramedPayment');
    Route::post('/payProgramedPayment',[ActiveController::class,'payProgramedPayment'])->name('payProgramedPayment');
    
    Route::any('/cashFlow',[ReportsController::class,'cashFlow'])->name('cashFlow');
    Route::post('/cashFlowReport',[ReportsController::class,'cashFlowReport'])->name('cashFlowReport');
    Route::get('/bankBalance',[ReportsController::class,'bankBalance'])->name('bankBalance');
    Route::post('/bankBalanceReport',[ReportsController::class,'bankBalanceReport'])->name('bankBalanceReport');
    Route::post('/parcelsReport',[ReportsController::class,'parcelsReport'])->name('parcelsReport');

    

    Route::get('/foo', function () {
        Artisan::call('storage:link');
    });

    
});


