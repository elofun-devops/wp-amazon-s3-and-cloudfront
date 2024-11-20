<?php

namespace DeliciousBrains\WP_Offload_Media\Providers\Storage;

use WP_Error;

class VNG_Provider extends AWS_Provider
{
    protected static $provider_name = 'VNG';
    protected static $provider_short_name = 'VNG';
    protected static $provider_key_name = 'vng';
    protected static $service_name = 'Storage';
    protected static $service_short_name = 'Storage';
    protected static $service_key_name = 'storage';
    protected static $provider_service_name = '';
    protected static $provider_service_quick_start_slug = 'vng-storage-quick-start-guide';
    protected static $access_key_id_constants = array(
        'AS3CF_VNG_ACCESS_KEY_ID',
    );
    protected static $secret_access_key_constants = array(
        'AS3CF_VNG_SECRET_ACCESS_KEY',
    );
    protected static $use_server_roles_constants = array();
    protected static $block_public_access_supported = false;
    protected static $object_ownership_supported = false;
    protected static $regions = array(
        'han01' => 'HAN01',
        'hcm03' => 'HCM03',
        'hcm04' => 'HCM04',
    );
    protected static $region_required = true;
    protected static $default_region = 'hcm04';
    protected $default_domain = 'vstorage.vngcloud.vn';

    protected function init_client_args(array $args)
    {
        if (empty($args['endpoint'])) {
            $args['region'] = empty($args['region']) ? static::get_default_region() : $args['region'];
            $args['endpoint'] = 'https://' . $args['region'] . '.' . $this->get_domain();
        }

        $this->client_args = $args;

        return $this->client_args;
    }

    protected function init_service_client_args(array $args)
    {
        return $args;
    }

    public function block_public_access(string $bucket, bool $block)
    {
    }

    public function enforce_object_ownership(string $bucket, bool $enforce)
    {
    }

    public function create_bucket(array $args)
    {
        if (!empty($this->client_args['region']) && 'us-east-1' === $this->client_args['region']) {
            parent::create_bucket($args);
        } else {
            $client_args = $this->client_args;
            $client_args['region'] = 'us-east-1';
            unset($args['LocationConstraint']); // Not needed and breaks signature.
            $this->get_client($client_args, true)->create_bucket($args);
        }
    }

    public function get_bucket_location(array $args)
    {
        return strip_tags(parent::get_bucket_location($args));
    }

    protected function url_prefix($region = '', $expires = null)
    {
        return $region;
    }

    protected function get_console_url_suffix_param(string $bucket = '', string $prefix = '', string $region = ''): string
    {
        return '';
    }
}
