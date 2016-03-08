<?php

namespace Application\Service;

class FileService {

    public static function makePath($pathname, $is_filename = false) {
        if ($is_filename) {
            $pathname = substr($pathname, 0, strrpos($pathname, DS));
        }

        if (is_dir($pathname) || empty($pathname)) {
            return true;
        }

        $pathname = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $pathname);
        if (is_file($pathname)) {
            trigger_error('mkdirr() File exists', E_USER_WARNING);
            return false;
        }

        $next_pathname = substr($pathname, 0, strrpos($pathname, DIRECTORY_SEPARATOR));

        if (self::makePath($next_pathname)) {
            if (!file_exists($pathname)) {
                $old = umask(0);
                $r = mkdir($pathname, 0777);
                umask($old);
                return $r;
            }
        }
        return false;
    }

    public static function getPathType($filename) {
        if (file_exists($filename) && is_file($filename)) {
            return 0;
        }

        if (file_exists($filename) && is_dir($filename)) {
            return 1;
        }

        return false;
    }

    public static function deleteFile($path) {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public static function createFile($path) {
        fopen($path, 'w');
    }

    /**
     * サーバーOSはWindowsかを判断する
     */
    public static function isWindows() {
        if (DIRECTORY_SEPARATOR == '\\') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * コマンド実行し、結果を取得
     * @param type $cmd
     * @return type array(return_val => $output_string)
     */
    public static function getCmdResult($cmd) {
        $output = array();
        $return_var = null;
        exec($cmd, $output, $return_var);
        $str_output = implode("\n", $output);
        return array(
            'return_val' => $return_var,
            'output' => $str_output
        );
    }

    /**
     * ディレクトリーを削除
     * @param type $path
     */
    public static function deleteDir($path) {
        if (self::isWindows()) {
            $path = str_replace("/", "\\", $path);
        }
        exec("rm -rf {$path}");
    }

    /**
     * ファイルまたディレクトリを移動
     * @param type $old 変更前パス
     * @param type $new 変更後パス
     */
    public static function renameDir($old, $new) {
        if (static::isWindows()) {
            $path = str_replace("/", "\\", $path);
        }
        exec("mv {$old} {$new}");
    }

    /**
     * ディレクトリーを削除
     * @param type $path
     */
    public static function removeDirectory($dir) {
        if ($handle = opendir("$dir")) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir("$dir/$item")) {
                        removeDirectory("$dir/$item");
                    } else {
                        unlink("$dir/$item");
                    }
                }
            }
            closedir($handle);
            rmdir($dir);
        }
    }

    /**
     * FTPアップロード
     * @param type $path
     */
    public static function uploadToFtp($remote_file, $local_file)
    {
        $ftps = include 'config/ftp.config.php';
        foreach ($ftps as $ftp) {
            $conn_id = @ftp_connect($ftp['ftp_server']);
            //$login_result = ftp_login($conn_id, $ftp['ftp_user_name'], $ftp['ftp_user_pass']);
            if(@ftp_login($conn_id, $ftp['ftp_user_name'], $ftp['ftp_user_pass'])){
                ftp_pasv($conn_id, true);
                if (!ftp_put($conn_id, $ftp['ftp_root_path'].$remote_file, $local_file, FTP_BINARY)) {
                    throw new \Exception('FTPアップロード');
                }
            }else{
                throw new \Exception('FTP接続');
            }

            ftp_close($conn_id);
        }
    }

    /**
     * FTPアップロード
     * @param type $path
     */
    //public static function deleteFromFtp(string $remote_file)
    public static function deleteFromFtp($remote_file)
    {
        $ftps = include 'config/ftp.config.php';

        foreach ($ftps as $ftp) {
            $conn_id = @ftp_connect($ftp['ftp_server']);
            //$login_result = ftp_login($conn_id, $ftp['ftp_user_name'], $ftp['ftp_user_pass']);
            if(@ftp_login($conn_id, $ftp['ftp_user_name'], $ftp['ftp_user_pass'])){
                ftp_pasv($conn_id, true);
                if (!ftp_delete($conn_id, $ftp['ftp_root_path'].$remote_file)) {
                    throw new \Exception('FTPからファイルの削除が失敗しました。');
                }
            }else{
                throw new \Exception('FTP接続に失敗しました。');
            }
            
            ftp_close($conn_id);
        }

    }
    
    /**
     * csvファイルを作成する
     * @param type $dir
     * @param type $cdvData
     */
    public static function createCSV($dir, $cdvData) {
        $csv = "";
        foreach ($cdvData as $val) {
            $csv .= $val;
            $csv .= "\n";
        }
        mb_convert_variables('cp932', 'utf-8', $csv);
        try {
            // CSVファイルを追記モードで開く
            $fp = fopen($dir, 'w');
            flock($fp, LOCK_EX);
            fwrite($fp, $csv);
        } catch (Exception $ex) {
            throw new \Exception('CSVの作成に失敗しました。');
        }
        fclose($fp);
    }


}
