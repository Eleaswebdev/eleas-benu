<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v8/services/campaign_label_service.proto

namespace GPBMetadata\Google\Ads\GoogleAds\V8\Services;

class CampaignLabelService
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
        \GPBMetadata\Google\Api\Client::initOnce();
        \GPBMetadata\Google\Protobuf\Any::initOnce();
        \GPBMetadata\Google\Rpc\Status::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
6google/ads/googleads/v8/resources/campaign_label.proto!google.ads.googleads.v8.resourcesgoogle/api/resource.protogoogle/api/annotations.proto"�
CampaignLabelE
resource_name (	B.�A�A(
&googleads.googleapis.com/CampaignLabel@
campaign (	B)�A�A#
!googleads.googleapis.com/CampaignH �:
label (	B&�A�A 
googleads.googleapis.com/LabelH�:l�Ai
&googleads.googleapis.com/CampaignLabel?customers/{customer_id}/campaignLabels/{campaign_id}~{label_id}B
	_campaignB
_labelB�
%com.google.ads.googleads.v8.resourcesBCampaignLabelProtoPZJgoogle.golang.org/genproto/googleapis/ads/googleads/v8/resources;resources�GAA�!Google.Ads.GoogleAds.V8.Resources�!Google\\Ads\\GoogleAds\\V8\\Resources�%Google::Ads::GoogleAds::V8::Resourcesbproto3
�
=google/ads/googleads/v8/services/campaign_label_service.proto google.ads.googleads.v8.servicesgoogle/api/annotations.protogoogle/api/client.protogoogle/api/field_behavior.protogoogle/api/resource.protogoogle/rpc/status.proto"`
GetCampaignLabelRequestE
resource_name (	B.�A�A(
&googleads.googleapis.com/CampaignLabel"�
MutateCampaignLabelsRequest
customer_id (	B�AQ

operations (28.google.ads.googleads.v8.services.CampaignLabelOperationB�A
partial_failure (
validate_only ("{
CampaignLabelOperationB
create (20.google.ads.googleads.v8.resources.CampaignLabelH 
remove (	H B
	operation"�
MutateCampaignLabelsResponse1
partial_failure_error (2.google.rpc.StatusL
results (2;.google.ads.googleads.v8.services.MutateCampaignLabelResult"2
MutateCampaignLabelResult
resource_name (	2�
CampaignLabelService�
GetCampaignLabel9.google.ads.googleads.v8.services.GetCampaignLabelRequest0.google.ads.googleads.v8.resources.CampaignLabel"H���20/v8/{resource_name=customers/*/campaignLabels/*}�Aresource_name�
MutateCampaignLabels=.google.ads.googleads.v8.services.MutateCampaignLabelsRequest>.google.ads.googleads.v8.services.MutateCampaignLabelsResponse"W���8"3/v8/customers/{customer_id=*}/campaignLabels:mutate:*�Acustomer_id,operationsE�Agoogleads.googleapis.com�A\'https://www.googleapis.com/auth/adwordsB�
$com.google.ads.googleads.v8.servicesBCampaignLabelServiceProtoPZHgoogle.golang.org/genproto/googleapis/ads/googleads/v8/services;services�GAA� Google.Ads.GoogleAds.V8.Services� Google\\Ads\\GoogleAds\\V8\\Services�$Google::Ads::GoogleAds::V8::Servicesbproto3'
        , true);
        static::$is_initialized = true;
    }
}

