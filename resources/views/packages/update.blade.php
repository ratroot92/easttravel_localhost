@extends('layouts.admin')
<script src="{{url('ckeditor/ckeditor.js')}}"></script>
@section('content')
<div class="sb2-2">
    <div class="sb2-2-2">
      <ul>
              <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
              </li>
              <li class="active-bre"><a href="{{route('package.view')}}"> All Packages</a>
              </li>
              <li class="active-bre"><a href="{{route('package.add')}}"> Add New Package</a></li>
            
              <li class="active-bre"><a href="{{route('package.category')}}">All Package Categories</a>
              <li class="active-bre"><a href="{{route('package.addcategory')}}">Add Package Categories</a>
              <li class="page-back"><a href="index.html"><i class="fa fa-backward" aria-hidden="true"></i> Back</a>
              </li>
              </ul>
</div>

<div class="container-fluid">
<div class="row">
<div class="col-md-12">
           <br />
<div id="app">
@include('flash-message')
@yield('content')


<ul>
     @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
     @endforeach
</ul>
</div>        
</div>    
</div>
</div>

<div class="sb2-2-add-blog sb2-2-1">
<div class="box-inn-sp">
<div class="inn-title">
    <h4>{{ucfirst($action ?? '' )}} Package</h4>
    <p>Airtport Hotels The Right Way To Start A Short Break Holiday</p>
</div>
<div class="bor">
    <form method="post" enctype="multipart/form-data" action="{{route('package.edit')}}">
        {{csrf_field()}}


         <div class="row">
            <div class="input-field col s12">
                <input id="id" name="id" type="text"  class="validate" value="{{$package->id}}" readonly="" />
                <label for="id">Activty ID</label>
            </div>
        </div>


        <div class="row">
            <div class="input-field col s12">
                <input id="name" name="name" type="text" value="{{$package->name}}" class="validate" required/>
                <label for="name">Activty Name</label>
            </div>
        </div>



{{-- 
        <div class="row">
            <div class="input-field col s12">
                <input id="city" name="city" type="text" name="city" class="validate" value="{{$package->city}}"  required/>
                <label for="city" >City Name</label>
            </div>
        </div>

 --}}




<div class="row">
    <div class="input-field col s12 font-weight-bold {{ $errors->has('country') ? 'has-error' : '' }}">
        <select  multiple="multiple" name="country[]" id="country" required>
            @foreach($country_list as $item)
            @if (in_array($item->name, $item_city))
            <option selected="true" value="{{$item->name}}">{{$item->name}}</option>
            @else
            <option value="{{$item->name}}">{{$item->name}}</option>
            @endif
            @endforeach
        </select>
        <label>Select Country</label>
    </div>
</div>


<div class="row">
            <div class="input-field col s12 font-weight-bold">
                <input id="city" name="city" type="text" value="@foreach($citynames as $item){{$item->name}},@endforeach" class="validate" required/>
                <label for="city">City Name</label>
            </div>
        </div>




<div class="row">
    <div class="input-field col s12 font-weight-bold {{ $errors->has('cat') ? 'has-error' : '' }}">
        <select  multiple="multiple" name="cat[]" id="cat" required>
            @foreach($packagecat as $item)
            @if (in_array($item->name, $item_category))
            <option selected="true" value="{{$item->name}}">{{$item->name}}</option>
            @else
            <option value="{{$item->name}}">{{$item->name}}</option>
            @endif
            @endforeach
        </select>
        <label>Select Category</label>
    </div>
</div>







{{-- 

        <div class="row">
            <div class="input-field col s12">
                <select  multiple name="cat[]" id="cat" required/>
                    <option  disabled>Choose Category</option>
                    @foreach($packagecat as $item)
                    <option  value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
                <label>Select Category</label>
            </div>





            
        </div> --}}




















        <div class="row">
            <span class="alert-warning  pull-right ">previous files will be replaced  </span>
            <div class="input-field col s12">
                <div class="file-field">
                    <div class="btn">
                        <span>File <i class="fa fa-file-image-o"></i></span>
                        <input type="file" name="file[]" id="file[]" multiple="multiple" />
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" placeholder="Upload Package Banner" />
                    </div>
                </div>
            </div>













            <div class="input-field col s12">
                 <span class="alert-warning  pull-right ">previous images will be replaced  </span>
                <div class="file-field">
                    <div class="btn">
                        <span>Gallery Images <i class="fa fa-file-image-o"></i></span>
                        <input type="file" name="img[]" id="img[]" multiple="multiple" />
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload Images" />
                    </div>
                  
                </div>
            </div>
        </div>






        








<div class="row">

        <div class="input-field col s12">
            <input type="text" class="form-control" name="duration" id="duration" placeholder="5 Days" value="{{$package->duration}}" required/>
    
            <label for="duration">Duration</label>
        </div>
</div>






        <div class="row">
            <div class="input-field col s12">
                <input  type="text" name="disc" id="disc" class="validate"value="{{$package->disc}}"  required/>
                <label for="disc">package Discount</label>
            </div>
        </div>







        <div class="row">
            <div class="input-field col s12">
                <input type="text" name="price" id="price" class="validate" value="{{$package->price}}"  /required/>
                <label for="price">Package Price</label>
            </div>
        </div>

  

        <div class="row">
            <div class="input-field col s12">
                <input  type="text" name="grpsize" id="grpsize" class="validate" placeholder="Min:2 / Max:16" minlength="2" maxlength="16"  name="grpsize" value="{{$package->grpsize}}"  required="required"/>
                <label for="grpsize">Group Size</label>
            </div>
        </div>





        <div class="row">
            <div class="input-field col s12">
                <input  type="text"  class="validate" placeholder="Vienna / Vienna " id="startloc"  name="startloc" value="{{$package->startloc}}" required="required"/>
                <label for="startloc">Start In /End In</label>
            </div>
        </div>






<div class="row">

        <div class="input-field col s12">
            <input type="text" class="validate" id="tranportdetails"  name="tranportdetails"  required="required" placeholder="Van" value="{{$package->tranportdetails}}" />
            <label for="tranportdetails">Transport Details </label>
        </div>
</div>














<div class="row">

        <div class="input-field col s12">
            <input type="text" class="validate" id="tourlanguage"  name="tourlanguage" placeholder="English"  value="{{$package->tourlanguage}}" required="required" />
            <label for="tourlanguage">Tour Languages </label>
        </div>
</div>
   




      



<div class="row">

        <div class="input-field col s12">
            <input type="text" class="validate" id="accomodationdetails"  name="accomodationdetails"   placeholder="Included / Not included " value="{{$package->accomodationdetails}}"  required="required"/>
            <label for="accomodationdetails">Accomodation Details  </label>
        </div>
</div>











<div class="row">

        <div class="input-field col s12">
            <input type="text" class="validate" id="mealdetails"  name="mealdetails"   placeholder="Included / Not included "  value="{{$package->mealdetails}}"  required="required"/>
            <label for="mealdetails">Meals Detils </label>
        </div>
</div>











<div class="row">

        <div class="input-field col s12">
            <input type="text" class="validate" id="guidedetails"  name="guidedetails"  required="required" placeholder="Including English Speaker" value="{{$package->guidedetails}}"  />
            <label for="guidedetails">Tour Guide</label>
        </div>
</div>










        
        <div class="row">
            <br>
            <label for="textarea1">Tour Code        </label>
            <div class="input-field col s12">
                 <input type="text" id="tourcode" class="validate" name="tourcode"  required="required" value="{{$package->tourcode}}"/>
            </div>
        </div>





      



      

     


        <div class="row">
            <br>
            <label for="textarea1">Tour Style Details      </label>
            <div class="input-field col s12">
                <input type="text" id="tourstyle" class="validate" name="tourstyle" value="{{$package->tourstyle}}" required="required"/>
            </div>
        </div>


         












<!--<div class="row">-->

<!--        <div class="input-field col s12">-->
<!--            <input type="text" class="validate" id="tourlanguage"  name="tourlanguage" placeholder="English" value="{{$package->tourlanguage}}" required="required"/>-->
         
<!--            <label for="tourlanguage">Tour Languages </label>-->
<!--        </div>-->
<!--</div>-->





        <div class="row">
            <br>
            <label for="textarea1">Availibility  Date Details      </label>
            <div class="input-field col s12">
                <input type="text" id="avalibilitydetails" class="validate" name="avalibilitydetails" required="required" value="{{$package->avalibilitydetails}}"/>
            </div>
        </div>












          
  
<div class="row">
            <br>
            <label for="textarea1">Description</label>
            <div class="input-field col s12 font-weight-bold">
                <textarea id="desc" class="ckeditor" name="desc" required="required" >{{$package->desc}}</textarea>
            </div>
        </div>


          
  
<!--<div class="row">-->
<!--            <br>-->
<!--            <label for="textarea1">About The Tour</label>-->
<!--            <div class="input-field col s12">-->
<!--                <textarea id="about" class="ckeditor" name="about" required="required" >{{$package->about}}</textarea>-->
<!--            </div>-->
<!--        </div>-->


        <div class="row">
            <br>
            <label for="textarea1">Destinations</label>
            <div class="input-field col s12">
                <textarea id="destinations" class="ckeditor" name="destinations" required="required" >{{$package->destinations}}</textarea>
            </div>
        </div>













        <div class="row">
            <br>
            <label for="textarea1"> Itinerary</label>
            <div class="input-field col s12 font-weight-bold">
                <textarea id="day_detail" class="ckeditor" name="day_detail" required="required">{{$package->day_detail}}</textarea>
            </div>
        </div>






        <div class="row">
            <br>
            <label for="textarea1"> Exclusion</label>
            <div class="input-field col s12 font-weight-bold">
                <textarea id="exclusion" class="ckeditor" name="exclusion" required="required">{{$package->exclusion}}</textarea>
            </div>
        </div>



        <div class="row">
            <br>
            <label for="textarea1"> Inclusion</label>
            <div class="input-field col s12 font-weight-bold">
                <textarea id="inclusion" class="ckeditor" name="inclusion" required="required">{{$package->inclusion}}</textarea>
            </div>
        </div>
       




















        <div class="row">
            <br>
            <label for="textarea1">Terms and Conditions </label>
            <div class="input-field col s12">
                <textarea id="terms" class="ckeditor" name="terms" required="required">{{$package->terms}}</textarea>
            </div>
        </div>










        <div class="row">
            <br>
            <label for="textarea1">Payment Policy  </label>
            <div class="input-field col s12">
                <textarea id="payment_policy" class="ckeditor" name="payment_policy" required="required">{{$package->payment_policy}}</textarea>
            </div>
        </div>












        <div class="row">
            <br>
            <label for="textarea1">Payment Methods  </label>
            <div class="input-field col s12">
                <textarea id="payment_methods" class="ckeditor" name="payment_methods" required="required">{{$package->payment_methods}}</textarea>
            </div>
        </div>











         <div class="row">
            <br>
            <label for="textarea1">Cancellation Policy   </label>
            <div class="input-field col s12">
                <textarea id="cancellation_policy" class="ckeditor" name="cancellation_policy" required="required">{{$package->cancellation_policy}}</textarea>
            </div>
        </div>








         <div class="row">
            <br>
            <label for="textarea1">Visa Information    </label>
            <div class="input-field col s12">
                <textarea id="visa_info" class="ckeditor" name="visa_info" required="required">{{$package->visa_info}}</textarea>
            </div>
        </div>









         <div class="row">
            <br>
            <label for="textarea1">Important Notes      </label>
            <div class="input-field col s12">
                <textarea id="notes" class="ckeditor" name="notes" required="required">{{$package->notes}}</textarea>
            </div>
        </div>












         <div class="row">
            <br>
            <label for="textarea1">Ask A Question       </label>
            <div class="input-field col s12">
                <textarea id="questions" class="ckeditor" name="questions" required="required">{{$package->questions}}</textarea>
            </div>
        </div>








       
        <div class="row">
            <div class="input-field col s12">
                <textarea id="" class="materialize-textarea" name="code" id="code" placeholder='<iframe id="regiondo-booking-widget" data-width="338px" data-url="https://eastravels.regiondo.com/bookingwidget/vendor/23641/id/94437" data-title="Berlin in 7 Days" style="margin: 0px; padding: 0px; overflow: hidden; vertical-align: top; border: 0px; background: transparent; width: 338px; height: 455px;" data-processed="1" frameborder="0" src="https://eastravels.regiondo.com/bookingwidget/vendor/23641/id/94437?bookingwidget=1&amp;secure=1" data-id="1"></iframe>' /required>{{$package->code}}</textarea>
                <label for="textarea1">Code:</label>
            </div>
        </div>





<div class="row">
     <span class="alert alert-info ">select which icons should be visible in list view  </span>
    <div class="input-field col s12 font-weight-bold {{ $errors->has('icons') ? 'has-error' : '' }}">
        <select  multiple="multiple" name="icons[]" id="icons" required>
            @foreach($all_icons as $item)
            @if (in_array($item->name, $item_icon))
            <option selected="true" value="{{$item->name}}">{{$item->name}}</option>
            @else
            <option value="{{$item->name}}">{{$item->name}}</option>
            @endif
            @endforeach
        </select>
        <label>Select icons</label>
    </div>
</div>



    



        <div class="row">
            <div class="input-field col s12">
              <select name="status" id="status" class="validate" required="required" >
                <option value="0" {{$package->status == '0'  ? 'selected' : ''}}>In-active</option>
                <option value="1"  {{$package->status == '1'  ? 'selected' : ''}}>Active</option>
              </select>
                <label for="status">package Status  </label>
            </div>
        </div>





        <div class="row">
            <div class="input-field col s12">
                <button type="submit" class="waves-effect waves-light btn-large">Update package<i class="fa fa-paper-plane"></i></button>
            </div>
        </div>
    </form>



</div>
</div>
</div>
</div>

@endsection