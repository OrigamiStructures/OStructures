<?php
namespace App\Model\Table;

use App\Model\Entity\Image;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Proffer;

/**
 * Images Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Articles
 */
class ImagesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('images');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Articles', [
            'foreignKey' => 'article_id'
        ]);
		$this->addBehavior('Proffer.Proffer', [
			'image' => [    // The name of your upload field
				'root' => WWW_ROOT . 'img', // Customise the root upload folder here, or omit to use the default
				'dir' => 'image_dir',   // The name of the field to store the folder
				'thumbnailSizes' => [ // Declare your thumbnails
					'square' => ['w' => 200, 'h' => 200],   // Define the size and prefix of your thumbnails
					'portrait' => ['w' => 100, 'h' => 300, 'crop' => true],     // Crop will crop the image as well as resize it
				],
				'thumbnailMethod' => 'imagick'  // Options are Imagick, Gd or Gmagick
			]
		]);
    }
	
	protected function _initializeSchema(\Cake\Database\Schema\Table $table)
	{
		$table->columnType('image', 'proffer.file');
		return $table;
	}
	

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('image');
            
        $validator
            ->allowEmpty('image_dir');
            
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->allowEmpty('mimetype');
            
        $validator
            ->allowEmpty('filesize');
            
        $validator
            ->add('width', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('width');
            
        $validator
            ->add('height', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('height');
            
        $validator
            ->allowEmpty('title');
            
        $validator
            ->allowEmpty('date');
            
        $validator
            ->allowEmpty('alt');
            
        $validator
            ->add('upload', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('upload');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['article_id'], 'Articles'));
        return $rules;
    }
	
}
