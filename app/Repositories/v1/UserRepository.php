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
            $extraHours = $calctime / 30;
            $extraMinutes = $calctime % 30;
            if($extraMinutes > 0) {
                $extraFee = ($extraHours * 5) + 5;
            } else {
                $extraFee = $extraHours * 5;
            }
        }
        if($totaltime > 3600) {
            $fineDays = ($totaltime - 3600) / 3600;
            $fineHours = ($totaltime - 3600) % 3600;
            if ($fineHours > 0) {
                $fine = ($fineDays * 100) + 100;
            } else {
                $fine = $fineDays * 100;
            }
        }
        $totalFare = $baseFare + $extraFee + $fine;
        $slot->status = 1;
        $slot->update();
        $user->token = $user->createToken('asApp')->plainTextToken;
        $user->fare = $totalFare;
        return $user;
    }
}
