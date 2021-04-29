<?php
namespace Thrift\CeCd\Sdk;

/**
 * Autogenerated by Thrift Compiler (0.13.0)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */

use Thrift\Exception\TProtocolException;
use Thrift\Type\TType;

class RpcService_callRpc_result
{
    static public $isValidate = false;

    static public $_TSPEC = array(
        0 => array(
            'var' => 'success',
            'isRequired' => false,
            'type' => TType::STRUCT,
            'class' => '\Thrift\CeCd\Sdk\ResponseData',
        ),
        1 => array(
            'var' => 'ex',
            'isRequired' => false,
            'type' => TType::STRUCT,
            'class' => '\Thrift\CeCd\Sdk\InvalidException',
        ),
    );

    /**
     * @var \Thrift\CeCd\Sdk\ResponseData
     */
    public $success = null;
    /**
     * @var \Thrift\CeCd\Sdk\InvalidException
     */
    public $ex = null;

    public function __construct($vals = null)
    {
        if (is_array($vals)) {
            if (isset($vals['success'])) {
                $this->success = $vals['success'];
            }
            if (isset($vals['ex'])) {
                $this->ex = $vals['ex'];
            }
        }
    }

    public function getName()
    {
        return 'RpcService_callRpc_result';
    }


    public function read($input)
    {
        $xfer = 0;
        $fname = null;
        $ftype = 0;
        $fid = 0;
        $xfer += $input->readStructBegin($fname);
        while (true) {
            $xfer += $input->readFieldBegin($fname, $ftype, $fid);
            if ($ftype == TType::STOP) {
                break;
            }
            switch ($fid) {
                case 0:
                    if ($ftype == TType::STRUCT) {
                        $this->success = new \Thrift\CeCd\Sdk\ResponseData();
                        $xfer += $this->success->read($input);
                    } else {
                        $xfer += $input->skip($ftype);
                    }
                    break;
                case 1:
                    if ($ftype == TType::STRUCT) {
                        $this->ex = new \Thrift\CeCd\Sdk\InvalidException();
                        $xfer += $this->ex->read($input);
                    } else {
                        $xfer += $input->skip($ftype);
                    }
                    break;
                default:
                    $xfer += $input->skip($ftype);
                    break;
            }
            $xfer += $input->readFieldEnd();
        }
        $xfer += $input->readStructEnd();
        return $xfer;
    }

    public function write($output)
    {
        $xfer = 0;
        $xfer += $output->writeStructBegin('RpcService_callRpc_result');
        if ($this->success !== null) {
            if (!is_object($this->success)) {
                throw new TProtocolException('Bad type in structure.', TProtocolException::INVALID_DATA);
            }
            $xfer += $output->writeFieldBegin('success', TType::STRUCT, 0);
            $xfer += $this->success->write($output);
            $xfer += $output->writeFieldEnd();
        }
        if ($this->ex !== null) {
            $xfer += $output->writeFieldBegin('ex', TType::STRUCT, 1);
            $xfer += $this->ex->write($output);
            $xfer += $output->writeFieldEnd();
        }
        $xfer += $output->writeFieldStop();
        $xfer += $output->writeStructEnd();
        return $xfer;
    }
}