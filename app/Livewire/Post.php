<?php

namespace App\Livewire;

use App\Models\Post as ModelsPost;
use Illuminate\Contracts\View\View;
use Livewire\Component;

use function Laravel\Prompts\select;

class Post extends Component
{
    //variable declarations 
    public $posts, $title, $description, $postId, $update_post = false, $add_post= false;
    
    // action listeners 
    protected $listeners = [
        'deletePostListener' => 'deletePost'
    ];

    // form rules 
    protected $rules = [
        'title' => 'required',
        'description' => 'required',        
    ];

    // reset field fuction 
    public function resetFields(){
        $this->title = '';
        $this->description = '';
    }
    
    // render post data 
    public function render() : View
    {
        $this->posts= ModelsPost::select('id', 'title', 'description')->get();
        return view('livewire.post');
    }

    // add post form 
    public function addPost(){
        $this->resetFields();
        $this->add_post = true;
        $this->update_post = false;
    }
    
    // store posts 
    public function storePost(){
        $this->validate();
        try {
            ModelsPost::create([
                'title' => $this->title,
                'description'=>$this->description,
            ]);
            // update session 
            session()->flash('success', 'Post Created Successfully!!');
            $this->resetFields();
            $this->add_post = false;
            
        } catch (\Exception $th) {
            session()->flash('error', 'Something went wrong!!');
        }
    }

    // show existing post 
    public function editPost($id){
        try {
                $post = ModelsPost::findOrFail($id);
                // @phpstan-ignore booleanNot.alwaysFalse
                if (!$post) {
                    session()->flash('error', 'Post Not Found!');
                }else{
                    $this->title=$post->title;
                    $this->description=$post->description;
                    $this->postId=$post->id;
                    $this->update_post=true;
                    $this->add_post=false;
                    
                }
            } catch (\Throwable $th) {
                session()->flash('error', 'Something Went Wrong!!');
            }  
    }   
    
    // update post
    public function updatePost(){
        $this->validate();
        try {
            ModelsPost::whereId($this->postId)->update([
                'title' => $this->title,
                'description' => $this->description,
            ]);
            session()->flash('success', 'Post Updated Successfully');
            $this->resetFields();
            $this->update_post = false;
            
        } catch (\Exception $th) {
            session()->flash('title', 'Something Went Wrong');
        }
    }
    
    // cancel add/edit form 
    public function cancelForm(){
        $this->add_post = false;
        $this->update_post = false;
        $this->resetFields();
    }
    
    public function deletePost($id){
        try {
            ModelsPost::find($id)->delete();
            session()->flash('success', 'Post Deleted Successfully');
        } catch (\Exception $th) {
            session()->flash('error','Something Went Wrong!!');
        }
    }
}