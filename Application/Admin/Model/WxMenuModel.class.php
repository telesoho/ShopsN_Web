<?php

namespace Admin\Model;

use Common\Model\BaseModel;

class WxMenuModel extends BaseModel
{
    private $del_status;


    private static $obj;
    public static $id_d;    //id

    public static $level_d;    //菜单级别

    public static $name_d;    //name

    public static $sort_d;    //排序

    public static $type_d;    //0 view 1 click

    public static $value_d;    //value

    public static $token_d;    //token

    public static $pid_d;    //上级菜单


    public static function getInitnation()
    {
        $class = __CLASS__;
        return static::$obj = !( static::$obj instanceof $class ) ? new static() : static::$obj;
    }

    public function getMaxId()
    {
        return $this->order( self::$id_d . ' desc' )->getField( self::$id_d );
    }

    public function getMenu()
    {
        $data       = $this->order( self::$id_d . ' asc' )->select();
        $first_menu = [ ];
        $sec_menu   = [ ];
        foreach ( $data as $k => $v ) {
            if ( $v[ self::$pid_d ] == 0 ) {
                $first_menu[] = $v;
            } else {
                $sec_menu[] = $v;
            }
        }
        unset( $data );
        $data[ 0 ] = $first_menu; //一级菜单
        $data[ 1 ] = $sec_menu;   //二级菜单
        return $data;

    }

    public function saveMenu( $data )
    {

        $str                = 1;
        $insert_data_status = 1;
        $update_data        = [ ];
        $insert_data        = [ ];
        //$data = $this->setData($data);
        foreach ( $data as $k => $v ) {
            if ( $v[ 'id' ] ) {
                $update_data[] = $v;
                continue;
            }
            $insert_data[] = $v;
        }


        //如果存在更新的数据,则更新
        if ( $update_data ) {
            foreach ( $update_data as $k => $v ) {

                if ( $this->save( $v ) === false ) {
                    $str += 1;
                }

            }
        }

        //如果存在添加的数据,则添加
        if ( $insert_data ) {

            if ( !$this->addAll( $insert_data ) ) {
                $insert_data_status += 1;
            }
        }


        if ( $str === 1 && $insert_data_status === 1 ) {
            return true;
        }
        return false;

    }

    /** [dis] 检测删除的
     * @param $id 需要检测的菜单id
     */
    public function delMenu( $id )
    {
        $pid = $this->where( 'id = ' . $id )->getField( 'pid' );
        if ( $pid == 0 ) {
            $info = $this->where( 'pid = ' . $id )->getField( 'id' );
            if ( $info ) {
                return 3;//一级菜单下有二级菜单,不能删除
            }
        }
        return $this->delete( $id );//删除子菜单,以及没有子菜单的一级菜单
    }


}