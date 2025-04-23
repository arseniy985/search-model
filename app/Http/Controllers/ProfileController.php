<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Stripe\Customer as StripeCustomer;

class ProfileController extends Controller
{
    public function view(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;

        if ($customer) {
            $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
            $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        } else {
            $shippingAddress = new CustomerAddress(['type' => AddressType::Shipping]);
            $billingAddress = new CustomerAddress(['type' => AddressType::Billing]);
        }

        $countries = Country::query()->orderBy('name')->get();
        return view('profile.view', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }

    public function store(ProfileRequest $request)
    {
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];

        /** @var \App\Models\User $user */
        $user = $request->user();

        try {
            DB::beginTransaction();
            
            // Create or update customer
            $customer = $user->customer;
            if (!$customer) {
                $customer = Customer::create([
                    'first_name' => $customerData['first_name'],
                    'last_name' => $customerData['last_name'],
                    'phone' => $customerData['phone'],
                    'user_id' => $user->id,
                    'status' => 'active'
                ]);
            } else {
                $customer->update([
                    'first_name' => $customerData['first_name'],
                    'last_name' => $customerData['last_name'],
                    'phone' => $customerData['phone']
                ]);
            }

            // Create or update shipping address
            $shippingAddress = $customer->shippingAddress;
            if (!$shippingAddress) {
                CustomerAddress::create([
                    'customer_id' => $customer->id,
                    'type' => AddressType::Shipping->value,
                    'address1' => $shippingData['address1'],
                    'address2' => $shippingData['address2'],
                    'city' => $shippingData['city'],
                    'state' => $shippingData['state'],
                    'zipcode' => $shippingData['zipcode'],
                    'country_code' => $shippingData['country_code']
                ]);
            } else {
                $shippingAddress->update([
                    'address1' => $shippingData['address1'],
                    'address2' => $shippingData['address2'],
                    'city' => $shippingData['city'],
                    'state' => $shippingData['state'],
                    'zipcode' => $shippingData['zipcode'],
                    'country_code' => $shippingData['country_code']
                ]);
            }

            // Create or update billing address
            $billingAddress = $customer->billingAddress;
            if (!$billingAddress) {
                CustomerAddress::create([
                    'customer_id' => $customer->id,
                    'type' => AddressType::Billing->value,
                    'address1' => $billingData['address1'],
                    'address2' => $billingData['address2'],
                    'city' => $billingData['city'],
                    'state' => $billingData['state'],
                    'zipcode' => $billingData['zipcode'],
                    'country_code' => $billingData['country_code']
                ]);
            } else {
                $billingAddress->update([
                    'address1' => $billingData['address1'],
                    'address2' => $billingData['address2'],
                    'city' => $billingData['city'],
                    'state' => $billingData['state'],
                    'zipcode' => $billingData['zipcode'],
                    'country_code' => $billingData['country_code']
                ]);
            }
            
            DB::commit();
            
            $request->session()->flash('flash_message', 'Profile was updated successfully.');
            return redirect()->route('profile');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile update error: ' . $e->getMessage());
            
            $request->session()->flash('error_message', 'Error updating profile. Please try again.');
            return redirect()->route('profile');
        }
    }
    
    public function passwordUpdate(PasswordUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $passwordData = $request->validated();

        $user->password = Hash::make($passwordData['new_password']);
        $user->save();

        $request->session()->flash('flash_message', 'Your password was updated successfully.');
        return redirect()->route('profile');
    }
}
