<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AvitoData
 *
 * @property int $id
 * @property string $avito_id
 * @property string $avito_my_id
 * @property string $title
 * @property string $category
 * @property string|null $goodstype
 * @property string|null $goodssubtype
 * @property string|null $bulkmaterialtype
 * @property string|null $address
 * @property string $contactphone
 * @property string|null $client_id
 * @property string|null $materials_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData filter(\App\Http\Filters\FilterInterface $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData query()
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereAvitoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereAvitoMyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereBulkmaterialtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereContactphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereGoodssubtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereGoodstype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereMaterialsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvitoData whereTitle($value)
 */
	class AvitoData extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[] $children
 * @property-read int|null $children_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Client
 *
 * @property int $id
 * @property string $name
 * @property string $phone_personal
 * @property string $phone_personal_other
 * @property string|null $avito_url
 * @property string|null $site
 * @property string $email
 * @property string|null $comment
 * @property string $phone_avito
 * @property string $phone_yula
 * @property string|null $cars
 * @property string|null $delivery_cost
 * @property string|null $cars_other
 * @property string|null $order_pay
 * @property string|null $adress
 * @property string|null $min_cost
 * @property string|null $min_cost_unique
 * @property string|null $comment_other
 * @property int|null $district_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $Products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\District[] $districts
 * @property-read int|null $districts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Town[] $towns
 * @property-read int|null $towns_count
 * @method static \Database\Factories\ClientFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAdress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAvitoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCarsOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCommentOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereMinCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereMinCostUnique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereOrderPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhoneAvito($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhonePersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhonePersonalOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhoneYula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereSite($value)
 */
	class Client extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\District
 *
 * @property int $id
 * @property string $name
 * @property-read \App\Models\Client|null $client
 * @method static \Illuminate\Database\Eloquent\Builder|District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|District query()
 * @method static \Illuminate\Database\Eloquent\Builder|District whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereName($value)
 */
	class District extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Nomenclature
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $images
 * @property string|null $cost
 * @property string|null $weight
 * @property int|null $client_id
 * @property int|null $product_id
 * @property-read \App\Models\Client|null $client
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductAvito[] $productsAvito
 * @property-read int|null $products_avito_count
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature query()
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nomenclature whereWeight($value)
 */
	class Nomenclature extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $cost
 * @property string|null $weight
 * @property string|null $material_type
 * @property int|null $client_id
 * @property-read \App\Models\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Nomenclature[] $nomenclatures
 * @property-read int|null $nomenclatures_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMaterialType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeight($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductAvito
 *
 * @property int $id
 * @property string $avito_id
 * @property string $avito_my_id
 * @property string $title
 * @property string $category
 * @property string|null $goodstype
 * @property string|null $goodssubtype
 * @property string|null $bulkmaterialtype
 * @property string|null $address
 * @property string $contactphone
 * @property string|null $client_id
 * @property string|null $materials_type_id
 * @property-read \App\Models\Nomenclature|null $nomenclature
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito filter(\App\Http\Filters\FilterInterface $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereAvitoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereAvitoMyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereBulkmaterialtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereContactphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereGoodssubtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereGoodstype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereMaterialsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAvito whereTitle($value)
 */
	class ProductAvito extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Town
 *
 * @property int $id
 * @property string $name
 * @property int $district_id
 * @property int $client_id
 * @property-read \App\Models\Client|null $client
 * @property-read \App\Models\District|null $district
 * @method static \Illuminate\Database\Eloquent\Builder|Town newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Town newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Town query()
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Town whereName($value)
 */
	class Town extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

