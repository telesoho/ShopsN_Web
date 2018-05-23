<?php

namespace Common\Controller\Update;

use Common\TraitClass\CurlTrait;


/**
 * Class UpdateController  更新版本类
 * @package Common\Controller
 */
class UpdateController
{
    private $zip;//对象

    private $fileName;//保存的文件名字
    private $downFile;//下载文件名字与$fileName一样,为了区分
    private $savePash;//保存的文件的路径
    private $url = 'http://code.shopsn.net/home/down/down';
    private $checkUrl = 'http://code.shopsn.net/home/down/checkVersion';
    use CurlTrait;

    public function init( $downFile )
    {
        $this->setDownFile( $downFile );
        $this->fileName = $downFile . '.zip';
        $this->savePash = './Uploads/zip/';
        $this->zip      = new \ZipArchive;
        return $this;
    }

    public function Update()
    {
       return $this->Unzip();
    }

    /** 接口
     * 获取版本信息
     */
    public function getVersion( $version )
    {
        if ( !$version ) {
            echo json_encode( [ 'status' => 0,msg => '无版本号' ] );
            die;
        }
        $data          = $this->requestWeb( $this->checkUrl,[ 'version' => $version ] );
        $data[ 'url' ] = $this->url;
        return $data;
    }

    /**
     * 解压下载的文件,并覆盖到项目,完成更新
     */
    private function Unzip()
    {

        if ( !$this->Download() ) {
            return 1;
        }

        $status = $this->zip->open( $this->savePash . $this->fileName );
        if ( $status === true ) {
            $this->zip->extractTo( './' );
            $this->zip->close();
            if ( $this->delDir() ) {
                return 200;
            }

        }elseif($status === 19){
            return 19;
        }

        return 3;

    }

    /**
     * 修改版本号,删除当前更新包
     */
    private function delDir()
    {
        $status  = file_put_contents( './Public/version/version.txt',str_replace( '.zip','',$this->downFile ) );//5 int
        $status2 = unlink( $this->savePash . $this->fileName );//true
        if ( $status && $status2 ) {
            return true;
        }
        return false;
    }

    /**下载所需要的升级包
     * @return bool
     */
    private function Download()
    {
        $data = $this->requestWeb( $this->url,[ 'fileName' => $this->downFile ],'get',false );
        //创建保存目录
        if ( !file_exists( $this->savePash ) && !mkdir( $this->savePash,0777,true ) ) {
            unset( $data );
            return false;
        }

        if ( !file_put_contents( $this->savePash . $this->fileName,$data ) ) {
            unset( $data );
            return false;
        }
        unset( $data );
        return true;

    }

    private function setDownFile( $downFile )
    {
        if ( !empty( $downFile ) ) {
            $this->downFile = $downFile . '.zip';
            return true;
        }
        die( '缺少下载的文件名' );
    }


}
