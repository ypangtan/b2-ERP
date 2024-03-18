<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{
    Bank,
};

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = array(
            array('id' => '1','code' => 'maybank','title' => 'Maybank','status' => '1'),
            array('id' => '2','code' => 'cimb','title' => 'CIMB Group Holdings','status' => '1'),
            array('id' => '3','code' => 'pbe','title' => 'Public Bank Berhad','status' => '1'),
            array('id' => '4','code' => 'rhb','title' => 'RHB Bank','status' => '1'),
            array('id' => '5','code' => 'hlb','title' => 'Hong Leong Bank','status' => '1'),
            array('id' => '6','code' => 'ambank','title' => 'AmBank','status' => '1'),
            array('id' => '7','code' => 'uob','title' => 'UOB Malaysia','status' => '1'),
            array('id' => '8','code' => 'bankrakyat','title' => 'Bank Rakyat','status' => '1'),
            array('id' => '9','code' => 'ocbc','title' => 'OCBC Bank Malaysia','status' => '1'),
            array('id' => '10','code' => 'hsbc','title' => 'HSBC Bank Malaysia','status' => '1'),
            array('id' => '11','code' => 'bankislam','title' => 'Bank Islam Malaysia','status' => '1'),
            array('id' => '12','code' => 'affin','title' => 'Affin Bank','status' => '1'),
            array('id' => '13','code' => 'alliance','title' => 'Alliance Bank Malaysia Berhad','status' => '1'),
            array('id' => '14','code' => 'scbm','title' => 'Standard Chartered Bank Malaysia','status' => '1'),
            array('id' => '15','code' => 'mbsb','title' => 'MBSB Bank Berhad','status' => '1'),
            array('id' => '16','code' => 'citi','title' => 'Citibank Malaysia','status' => '1'),
            array('id' => '17','code' => 'bsn','title' => 'Bank Simpanan Nasional (BSN)','status' => '1'),
            array('id' => '18','code' => 'agrobank','title' => 'Agrobank','status' => '1'),
            array('id' => '37','code' => 'alrajhi','title' => 'Al Rajhi Banking And Investment Corporation','status' => '1'),
            array('id' => '38','code' => 'bnp','title' => 'BNP Paribas (Malaysia) Berhad','status' => '1'),
            array('id' => '39','code' => 'muamalat','title' => 'Bank Muamalat (Malaysia) Berhad','status' => '1'),
            array('id' => '40','code' => 'bankamerica','title' => 'Bank Of America Malaysia Berhad','status' => '1'),
            array('id' => '41','code' => 'bankchina','title' => 'Bank of China (Malaysia) Berhad','status' => '1'),
            array('id' => '42','code' => 'chinacons','title' => 'China Construction Bank (Malaysia) Berhad','status' => '1'),
            array('id' => '43','code' => 'deutsche','title' => 'Deutsche Bank (Malaysia) Berhad','status' => '1'),
            array('id' => '44','code' => 'finexus','title' => 'Finexus Cards Sdn. Bhd.','status' => '1'),
            array('id' => '45','code' => 'icbc','title' => 'Industrial And Commercial Bank of China (ICBC)','status' => '1'),
            array('id' => '46','code' => 'jpmorgan','title' => 'JP Morgan Chase Bank Berhad','status' => '1'),
            array('id' => '47','code' => 'kuwaitfinance','title' => 'Kuwait Finance House (Malaysia) Berhad','status' => '1'),
            array('id' => '48','code' => 'mufg','title' => 'MUFG Bank (Malaysia) Bhd','status' => '1'),
            array('id' => '49','code' => 'mizuho','title' => 'Mizuho Bank (Malaysia) Berhad','status' => '1'),
            array('id' => '50','code' => 'sumitomo','title' => 'Sumitomo Mitsui Banking Corporation','status' => '1'),
            array('id' => '51','code' => 'touchngo','title' => 'Touch n Go eWallet','status' => '1')
        );

        foreach ( $banks as $bank ) {

            Bank::create( [
                'name' => $bank['title'],
                'key' => $bank['code'],
                'is_display' => 1,
                'status' => 10,
            ] );
        }
    }
}
