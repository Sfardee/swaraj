<?php

/**
 * @file
 * Contains \Drupal\custom_widget\Controller\FtpTest.
 */

namespace Drupal\custom_widget\Controller;

use Drupal\Core\Controller\ControllerBase;

class FtpTest extends ControllerBase
{
  public function ftpUploadTest()
  {
    // FTP server details
    $ftp_host   = '10.255.2.36';
    $ftp_username = 'ExternalVendorFTP';
    $ftp_password = 'Him_098$1*';
    
    // open an FTP connection
    $conn_id = ftp_ssl_connect($ftp_host) or die("Couldn't connect to $ftp_host");
    
    // login to FTP server
    $ftp_login = ftp_login($conn_id, $ftp_username, $ftp_password);
    

    // local & server file path
    $localFilePath  = '/mnt/swarajtractor/swarajprd_files/reports/SampleFlatFile.xlsx';
    $remoteFilePath = 'SampleFlatFile.xlsx';

    // try to upload file
    if(ftp_put($conn_id, $remoteFilePath, $localFilePath, FTP_BINARY)){
        echo "File transfer successful - $localFilePath<br>";
    }else{
        echo "There was an error while uploading $localFilePath<br>";
    }

    echo ftp_pwd($conn_id); // /public_html

    // get contents of the current directory
    $contents = ftp_nlist($conn_id, ".");
    // output $contents
    var_dump($contents);

    // change directory to public_html
    // ftp_chdir($conn_id, '.');
    //ftp_pasv($conn_id, true);
    
    // $localFilePath  = '/mnt/swarajtractor/swarajprd_files/reports/SampleFlatFile-2020-08-241.xlsx';
    // $remoteFilePath = 'SampleFlatFile-2020-08-24.xlsx';

    // // try to download $server_file and save to $local_file
    // if (ftp_get($conn_id, $localFilePath, $remoteFilePath, FTP_BINARY)) {
    //   echo "Successfully written to $localFilePath\n";
    // } else {
    //   echo "There was a problem\n";
    // }

    // $localFilePath  = '/mnt/swarajtractor/swarajprd_files/reports/SampleFlatFile-2020-08-242.xlsx';
    // $remoteFilePath = 'SampleFlatFile-2020-08-24-test.xlsx';

    // try to download $server_file and save to $local_file
    // if (ftp_get($conn_id, $localFilePath, $remoteFilePath, FTP_BINARY)) {
    //   echo "Successfully written to $localFilePath\n";
    // } else {
    //   echo "There was a problem\n";
    // }

    // close the connection
    ftp_close($conn_id);

    // echo '<hr>';

    // FTP to diff server
    /*
    $ftp_host   = 'ftp.dlptest.com';
    $ftp_username = 'dlpuser@dlptest.com';
    $ftp_password = 'eUj8GeW55SvYaswqUyDSm5v6N';

    // open an FTP connection
    $conn_id = ftp_connect($ftp_host) or die("Couldn't connect to $ftp_host");

    // login to FTP server
    $ftp_login = ftp_login($conn_id, $ftp_username, $ftp_password);
    

    // local & server file path
    $localFilePath  = '/mnt/swarajtractor/swarajprd_files/reports/SampleFlatFile.xlsx';
    $remoteFilePath = 'SampleFlatFile-2020-08-24.xlsx';

    // try to upload file
    if(ftp_put($conn_id, $remoteFilePath, $localFilePath, FTP_BINARY)){
        echo "File transfer successful - $localFilePath<br>";
    }else{
        echo "There was an error while uploading $localFilePath<br>";
    }

    echo ftp_pwd($conn_id); // /public_html

    // get contents of the current directory
    $contents = ftp_nlist($conn_id, ".");
    // output $contents
    var_dump($contents);

    // change directory to public_html
    // ftp_chdir($conn_id, '.');
    //ftp_pasv($conn_id, true);
    
    $localFilePath  = '/mnt/swarajtractor/swarajprd_files/reports/SampleFlatFile-2020-08-242.xlsx';
    $remoteFilePath = 'SampleFlatFile-2020-08-24.xlsx';

    // try to download $server_file and save to $local_file
    if (ftp_get($conn_id, $localFilePath, $remoteFilePath, FTP_BINARY)) {
      echo "Successfully written to $localFilePath\n";
    } else {
      echo "There was a problem\n";
    }

    // close the connection
    ftp_close($conn_id);
    */
    return;
  }
}