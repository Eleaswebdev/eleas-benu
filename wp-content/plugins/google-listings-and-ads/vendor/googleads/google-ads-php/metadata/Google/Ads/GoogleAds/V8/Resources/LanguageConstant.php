<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v8/resources/language_constant.proto

namespace GPBMetadata\Google\Ads\GoogleAds\V8\Resources;

class LanguageConstant
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();
        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Http::initOnce();
        \GPBMetadata\Google\Api\Annotations::initOnce();
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        \GPBMetadata\Google\Api\Resource::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
9google/ads/googleads/v8/resources/language_constant.proto!google.ads.googleads.v8.resourcesgoogle/api/resource.protogoogle/api/annotations.proto"�
LanguageConstantH
resource_name (	B1�A�A+
)googleads.googleapis.com/LanguageConstant
id (B�AH �
code (	B�AH�
name (	B�AH�

targetable	 (B�AH�:P�AM
)googleads.googleapis.com/LanguageConstant languageConstants/{criterion_id}B
_idB
_codeB
_nameB
_targetableB�
%com.google.ads.googleads.v8.resourcesBLanguageConstantProtoPZJgoogle.golang.org/genproto/googleapis/ads/googleads/v8/resources;resources�GAA�!Google.Ads.GoogleAds.V8.Resources�!Google\\Ads\\GoogleAds\\V8\\Resources�%Google::Ads::GoogleAds::V8::Resourcesbproto3'
        , true);
        static::$is_initialized = true;
    }
}

