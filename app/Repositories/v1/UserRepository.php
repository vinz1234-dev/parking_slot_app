<?php

namespace App\Repositories\v1;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Slot;
use Carbon\Carbon;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository
{
    /**
     * List all Users according to Role
     *
     * @param mixed $request
     *
     * @return [type]
     */
    public function index($request)
    {
        return User::all();
    }


    public function create($request, $file)
    {
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads',
            $fileName,
            'public'
        );
        $user->phone = $request['phone'];
        $user->license = $filePath;
        $user->vehicleno = $request['vehicleno'];
        $user->save();

        $slot = Slot::where('status', 0)->first();
        $slot->user_id = $user->id;
        $slot->start_time = Carbon::parse($request['start_time']);
        $slot->end_time = Carbon::parse($request['end_time']);
        $totaltime = $slot->start_time->diffInMinutes($slot->end_time);
        $baseFare = 10;
        $extraFee = 0;
        $fine = 0;
        $calctime = $totaltime - 150;
        if ($calctime > 0) {
            $extraHours = ceil($calctime / 30);
            $extraFee = $extraHours * 5;
        }
        if($totaltime > 1440) {
            $fineDays = ceil(($totaltime - 1440) / 1440);
            $fine = $fineDays * 100;
        }
        $totalFare = $baseFare + $extraFee + $fine;
        $slot->status = 1;
        $slot->update();
        $user->slot = $slot->name;
        $user->token = $user->createToken('asApp')->plainTextToken;
        $user->total_time = $totaltime . " Minutes";
        $user->base_fare = $baseFare;
        $user->extra_fare = $extraFee;
        $user->fine = $fine;
        $user->total_fare = $totalFare;
        return $user;
    }
}
