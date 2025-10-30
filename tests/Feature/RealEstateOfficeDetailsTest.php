<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\RealEstate;
use App\Models\RealEstateOfficesDetail;
use Illuminate\Support\Str;

class RealEstateOfficeDetailsTest extends TestCase
{
    use RefreshDatabase;

    private function createRealEstateOfficeUser(): array
    {
        $user = User::create([
            'name' => 'Test Real Estate User',
            'email' => Str::uuid().'@example.com',
            'phone' => '01000000000',
            'password' => bcrypt('secret'),
            'user_type' => 'real_estate',
            'is_approved' => 1,
        ]);

        $realEstate = RealEstate::create([
            'user_id' => $user->id,
            'type' => 'office',
        ]);

        $officeDetail = RealEstateOfficesDetail::create([
            'real_estate_id' => $realEstate->id,
            'office_name' => 'Test Office',
            'office_address' => '123 Street',
            'office_phone' => '01000000000',
            'tax_enabled' => false,
        ]);

        return [$user, $realEstate, $officeDetail];
    }

    public function test_show_endpoint_requires_authentication()
    {
        [$user, $realEstate, $officeDetail] = $this->createRealEstateOfficeUser();
        $response = $this->getJson('/api/real-estate-office-details/' . $officeDetail->id);
        $response->assertStatus(401);
    }

    public function test_show_endpoint_returns_office_detail_for_owner()
    {
        Storage::fake('public');
        [$user, $realEstate, $officeDetail] = $this->createRealEstateOfficeUser();
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/real-estate-office-details/' . $officeDetail->id);
        $response->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.id', $officeDetail->id);
    }

    public function test_update_logo_image_and_remove_commercial_register_back()
    {
        Storage::fake('public');
        [$user, $realEstate, $officeDetail] = $this->createRealEstateOfficeUser();

        // ضع صورة قديمة للسجل التجاري الخلفي
        $oldPath = 'real-estate/offices/commercial-register/back/old.jpg';
        Storage::disk('public')->put($oldPath, 'fake');
        $officeDetail->commercial_register_back_image = '/storage/' . $oldPath;
        $officeDetail->save();

        Sanctum::actingAs($user);

        $response = $this->patchJson('/api/real-estate-office-details/' . $officeDetail->id, [
            'logo_image' => UploadedFile::fake()->image('logo.jpg'),
            'remove_commercial_register_back_image' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJson(fn($json) => isset($json['data']['logo_image']));

        // تحقق من حذف الملف القديم
        $this->assertFalse(Storage::disk('public')->exists($oldPath));

        // تحقق من أن الصورة الجديدة تم تخزينها
        $newLogoUrl = $response->json('data.logo_image');
        $relative = str_replace('/storage/', '', $newLogoUrl);
        $this->assertTrue(Storage::disk('public')->exists($relative));

        // تأكد من أن الحقل صار null
        $this->assertNull($response->json('data.commercial_register_back_image'));
    }

    public function test_delete_owner_id_front_image_only()
    {
        Storage::fake('public');
        [$user, $realEstate, $officeDetail] = $this->createRealEstateOfficeUser();

        $oldPath = 'real-estate/offices/owner-id/front/old-front.jpg';
        Storage::disk('public')->put($oldPath, 'fake');
        $officeDetail->owner_id_front_image = '/storage/' . $oldPath;
        $officeDetail->save();

        Sanctum::actingAs($user);
        $response = $this->patchJson('/api/real-estate-office-details/' . $officeDetail->id, [
            'remove_owner_id_front_image' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', true);

        $this->assertFalse(Storage::disk('public')->exists($oldPath));
        $this->assertNull($response->json('data.owner_id_front_image'));
    }

    public function test_invalid_file_type_rejected_with_422()
    {
        Storage::fake('public');
        [$user, $realEstate, $officeDetail] = $this->createRealEstateOfficeUser();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->create('doc.pdf', 30, 'application/pdf');
        $response = $this->patchJson('/api/real-estate-office-details/' . $officeDetail->id, [
            'logo_image' => $file,
        ]);

        $response->assertStatus(422);
    }

    public function test_unauthorized_user_cannot_update_other_office()
    {
        Storage::fake('public');
        [$user, $realEstate, $officeDetail] = $this->createRealEstateOfficeUser();
        $otherUser = User::create([
            'name' => 'Other Real Estate User',
            'email' => Str::uuid().'@example.com',
            'phone' => '01000000001',
            'password' => bcrypt('secret'),
            'user_type' => 'real_estate',
            'is_approved' => 1,
        ]);
        Sanctum::actingAs($otherUser);

        $response = $this->patchJson('/api/real-estate-office-details/' . $officeDetail->id, [
            'office_name' => 'New Name',
        ]);

        $response->assertStatus(403);
    }
}