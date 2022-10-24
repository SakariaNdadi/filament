<?php

namespace App\Http\Livewire\Assessments;

use App\Models\Assessments\Certification;
use Filament\Forms;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\Assessments\JCP;
use App\Models\Assessments\Skill;
use App\Models\Assessments\JcpSkill;
use Closure;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

// Filament form inputs
use Filament\Forms\Components\TextInput,
    Filament\Forms\Components\Select,
    Filament\Forms\Components\MultiSelect,
    Filament\Forms\Components\Card,
    Filament\Forms\Components\Tabs,
    Filament\Forms\Components\Tabs\Tab,
    Filament\Forms\Components\Wizard,
    Filament\Forms\Components\Wizard\Step,
    Filament\Forms\Components\Textarea;
use Illuminate\Support\HtmlString;

class JcpSkillsForm extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public $j_c_p_id;
    public $skill_id;
    public $status;
    public $level;
    public $code;
    public $job_title;
    public $grade;
    public $job_purpose;
    public $jcp_name;
    public $skill_content;
    public $title;
    public $category_id;
    public $description;

    public $skills = [];

    public $digital;
    public $capabilities;
    public $knowledge;
    public $behaviours;
    public $education;
    public $industry;

    public $skill_id_array;

    public function mount(): void
    {
      $this->jcp = new JCP();

      $this->form->fill([
         'job_title' => $this->jcp->job_title,      
         'level' => $this->jcp->level,
         'code' => $this->jcp->code,
         'grade' => $this->jcp->grade,
         'job_purpose' => $this->jcp->job_purpose,
         'status' => $this->jcp->status,
      ]);
    }
    protected function getFormSchema(): array
    {   
        return [
            Wizard::make()
            ->schema([
                Step::make('Details')
                    ->icon('heroicon-o-user')
                    ->description('Details of the JCP')
                    ->schema([
                        Select::make('j_c_p_id')->label('JCP Name')
                        ->searchable()
                        ->reactive()
                        ->required()
                        ->options(JCP::all()->pluck('name','id'))
                        ->afterStateUpdated(function($set,$state){
                            $set('job_title', $state=JCP::where($state)->get('job_title'));
                            $set('level', JCP::all()->where($state)->get('level'));
                            $set('code', JCP::all()->find($state)->get('code'));
                            $set('grade', JCP::all()->where($state)->get('grade'));
                            $set('job_purpose', JCP::all()->where($state)->get('job_purpose'));
                            $set('status', JCP::all()->where($state)->get('status'));

                            // $set('job_title', $state);
                            // $set('level', $state);
                            // $set('code', $state);
                            // $set('grade', $state);
                            // $set('job_purpose', $state);
                            // $set('status', $state);
                        }),
                        
                        TextInput::make('job_title')->label('Job Title'),
                        
                        TextInput::make('level'),
                            
                        TextInput::make('code'),
                        
                        TextInput::make('grade'),
                        
                        Textarea::make('job_purpose'),
                        
                        TextInput::make('status'),
                    ]),

                // Step::make('JCP Qualifications Requirements')
                //     ->disableLabel()
                //     ->icon('heroicon-o-pencil')
                //     ->description('What qualifications are you looking for in the position?')
                //     ->schema([
                //         Tabs::make('')
                //             ->schema([
                //                 Tab::make('Educational Qualifications')
                //                     ->schema([
                //                         Card::make()
                //                             ->schema([
                //                                 MultiSelect::make('certification_id')
                //                                     // ->relationship('skill_title','skills')
                //                                     ->searchable()
                //                                     // ->multiple()
                //                                     ->options(Certification::where('category_id','2')->pluck('title'))
                //                                     ->preload(),
                //                             ])
                //                     ]),
                //                 Tab::make('Industrial Certifications')
                //                     ->schema([
                //                         Card::make()
                //                             ->schema([
                //                                 MultiSelect::make('certification_id')
                //                                     // ->relationship('skill_title','skills')
                //                                     ->searchable()
                //                                     // ->multiple()
                //                                     ->options(Certification::where('category_id','1')->pluck('title'))
                //                                     ->preload(),
                //                             ])
                //                     ]),
                //             ])
                //         ]),

                Step::make('Skills')
                    ->icon('heroicon-o-pencil')
                    ->description('Add skills/questions to be answered by the participant')
                    ->schema([
                        Tabs::make('')
                            ->schema([
                                Tab::make('digital')
                                    ->schema([
                                        Card::make()
                                            ->schema([
                                                MultiSelect::make('skill_id')
                                                    // ->relationship('jcp_skill','skill_id')
                                                    ->searchable()
                                                    ->options(Skill::where('category_id','3')->pluck('skill_title'))
                                                    ->preload(),
                                            ])
                                    ]),
                              //   Tab::make('capabilities')
                              //       ->schema([
                              //           Card::make()
                              //               ->schema([
                              //                   MultiSelect::make('capabilities')
                              //                       ->relationship('jcp_skills','skill_id')
                              //                       ->searchable()
                              //                       // ->multiple()
                              //                       ->options(Skill::where('category_id','2')->pluck('skill_title'))
                              //                       ->preload(),
                              //               ])
                              //       ]),
                              //   Tab::make('knowledge')
                              //       ->schema([
                              //           Card::make()
                              //               ->schema([
                              //                   MultiSelect::make('knowledge')
                              //                       ->relationship('jcp_skills','skill_id')
                              //                       ->searchable()
                              //                       // ->multiple()
                              //                       ->options(Skill::where('category_id','1')->pluck('skill_title'))
                              //                       ->preload(),
                              //               ])
                              //       ]),
                              //   Tab::make('behaviours')
                              //       ->schema([
                              //           Card::make()
                              //               ->schema([
                              //                   MultiSelect::make('behaviours')
                              //                       ->relationship('jcp_skills','skill_id')
                              //                       ->searchable()
                              //                       // ->multiple()
                              //                       ->options(Skill::where('category_id','3')->pluck('skill_title')),
                              //               ])
                              //       ]),
                            ])
                    ])
            ])
            ->submitAction(new HtmlString('<button type="submit"
                class="my-5 w-full flex justify-center bg-sky-900 text-gray-100 p-4  rounded-full tracking-wide
                font-semibold  focus:outline-none focus:shadow-outline hover:bg-sky-800 shadow-lg cursor-pointer transition ease-in duration-300">
                Save
                </button>')
                )
        ];
    }
    public function submit(): void
    {
        // Certification::create($this->form->getState());
        JcpSkill::create($this->form->getState());
        redirect()->route('my-assessments');
    }


    public function render()
    {

        return view('livewire.assessments.jcpSkills-form');
    }
}
