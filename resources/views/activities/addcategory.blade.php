@extends('layouts.admin')



@section('content')

    <div class="sb2-2">

        <div class="sb2-2-2">

               <ul>

              <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a>

              </li>

              <li class="active-bre"><a href="{{route('activity.view')}}"> All Activities</a>

              </li>

              <li class="active-bre"><a href="{{route('activity.add')}}"> Add New Activity</a></li>

            

              <li class="active-bre"><a href="{{route('activity.category')}}">All Activity Categories</a>

              <li class="active-bre"><a href="{{route('activity.addcategory')}}">Add Activity Categories</a>

              <li class="page-back"><a href="index.html"><i class="fa fa-backward" aria-hidden="true"></i> Back</a>

              </li>

              </ul>

        </div>

        <div class="sb2-2-add-blog sb2-2-1">

            <div class="box-inn-sp">

                <div class="inn-title">

                    <h4>Post</h4>

                    <p>Airtport Hotels The Right Way To Start A Short Break Holiday</p>

                </div>

                <div class="bor">

                    <form method="post"  action="{{route('activity.insertcategory')}}">

                        {{csrf_field()}}

                        <div class="row">

                            <div class="input-field col s12">

                                <input  name="name" id="name" type="text" class="validate" required/>

                                <label for="list-title">Category Name</label>

                            </div>

                        </div>

                        <div class="row">

                            <div class="input-field col s12">

                                <button type="submit" class="waves-effect waves-light btn-large">Create Activity Category <i class="fa fa-paper-plane"></i></button>

                            </div>

                        </div>





                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection

