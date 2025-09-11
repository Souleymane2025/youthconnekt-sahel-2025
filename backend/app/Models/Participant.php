<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
   use HasFactory;

   protected $fillable = [
   'participant_id','first_name','last_name','email','phone','whatsapp','country','province','city','type','status','organization','occupation','interests','experience','motivation','handicap','whatsapp_opt','photo_path','passport_path','cin_path'
   ];

   protected $casts = [
      'interests' => 'array'
   ];

   // Forcer l'inclusion des champs d'images même quand ils sont NULL
   protected $visible = [
      'id', 'participant_id', 'first_name', 'last_name', 'email', 'phone', 'whatsapp', 
      'country', 'province', 'city', 'type', 'status', 'organization', 'occupation', 
      'interests', 'experience', 'motivation', 'handicap', 'whatsapp_opt', 
      'photo_path', 'passport_path', 'cin_path', 'created_at', 'updated_at'
   ];
}
