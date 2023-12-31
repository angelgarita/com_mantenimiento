<?php
/**
 * @version    CVS: 1.0.3
 * @package    Com_Mantenimiento
 * @author     Angel Garitagotia <agaritagotiac@aemet.es>
 * @copyright  2023 Angel Garitagotia
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
namespace Aemet\Component\Mantenimiento\Administrator\Table;
// No direct access
defined('_JEXEC') or die;

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Access\Access;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table as Table;
use \Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use \Joomla\Database\DatabaseDriver;
use \Joomla\CMS\Filter\OutputFilter;
use \Joomla\CMS\Filesystem\File;
use \Joomla\CMS\Filesystem\Folder;
use \Joomla\Registry\Registry;
use \Aemet\Component\Mantenimiento\Administrator\Helper\MantenimientoHelper;
use \Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Uri\Uri;

/**
 * Estacion table
 *
 * @since 1.0.0
 */
class EstacionTable extends Table implements VersionableTableInterface, TaggableTableInterface
{
	use TaggableTableTrait;
	/**
	 * Check if a field is unique
	 *
	 * @param   string  $field  Name of the field
	 *
	 * @return bool True if unique
	 */
	private function isUnique ($field)
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query
			->select($db->quoteName($field))
			->from($db->quoteName($this->_tbl))
			->where($db->quoteName($field) . ' = ' . $db->quote($this->$field))
			->where($db->quoteName('id') . ' <> ' . (int) $this->{$this->_tbl_key});

		$db->setQuery($query);
		$db->execute();

		return ($db->getNumRows() == 0) ? true : false;
	}

	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_mantenimiento.estacion';
		parent::__construct('#__estaciones', 'id', $db);
		$this->setColumnAlias('published', 'state');

	}

	/**
	 * Get the type alias for the history table
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   1.0.0
	 */
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  Optional array or list of parameters to ignore
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     Table:bind
	 * @since   1.0.0
	 * @throws  \InvalidArgumentException
	 */
	public function bind($array, $ignore = '')
	{
		$date = Factory::getDate();
		$task = Factory::getApplication()->input->get('task');
		$user = Factory::getApplication()->getIdentity();


		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new Registry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new Registry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		// Support for multi file field: ficherote
		if (!empty($array['ficherote']))
		{
			if (is_array($array['ficherote']))
			{
				$array['ficherote'] = implode(',', $array['ficherote']);
			}
			elseif (strpos($array['ficherote'], ',') != false)
			{
				$array['ficherote'] = explode(',', $array['ficherote']);
			}
		}
		else
		{
			$array['ficherote'] = '';
		}

		if (!$user->authorise('core.admin', 'com_mantenimiento.estacion.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_mantenimiento/access.xml',
				"/access/section[@name='estacion']/"
			);
			$default_actions = Access::getAssetRules('com_mantenimiento.estacion.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
				if (key_exists($action->name, $default_actions))
				{
					$array_jaccess[$action->name] = $default_actions[$action->name];
				}
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Method to store a row in the database from the Table instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.
	 * If no primary key value is set a new row will be inserted into the database with the properties from the Table instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function store($updateNulls = true)
	{
		return parent::store($updateNulls);
	}

	/**
	 * This function convert an array of Access objects into an rules array.
	 *
	 * @param   array  $jaccessrules  An array of Access objects.
	 *
	 * @return  array
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
					$actions[$group] = ((bool)$allow);
				}
			}

			$rules[$action] = $actions;
		}

		return $rules;
	}

	/**
	 * Overloaded check function
	 *
	 * @return bool
	 */
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		// Check if ind_climatologico is unique
		if (!$this->isUnique('ind_climatologico'))
		{
			throw new \Exception('Your <b>ind_climatologico</b> item "<b>' . $this->ind_climatologico . '</b>" already exists');
		}
        $this->variables = implode(',', (array) $this->variables);

		// Support multi file field: ficherote
		$app = Factory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');
		if (empty($files['ficherote'][0])){
			$temp = $files;
			$files = array();
			$files['ficherote'][] = $temp['ficherote'];
		}

		if ($files['ficherote'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = MantenimientoHelper::getFiles($this->id, $this->_tbl, 'ficherote');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/images/fotos_estaciones/'.$this->ind_climatologico . '/'. $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->ficherote = "";

			foreach ($files['ficherote'] as $singleFile )
			{
				jimport('joomla.filesystem.file');

				// Check if the server found any error.
				$fileError = $singleFile['error'];
				$message = '';

				if ($fileError > 0 && $fileError != 4)
				{
					switch ($fileError)
					{
						case 1:
							$message = Text::_('File size exceeds allowed by the server');
							break;
						case 2:
							$message = Text::_('File size exceeds allowed by the html form');
							break;
						case 3:
							$message = Text::_('Partial upload error');
							break;
					}

					if ($message != '')
					{
						$app->enqueueMessage($message, 'warning');

						return false;
					}
				}
				elseif ($fileError == 4)
				{
					if (isset($array['ficherote']))
					{
						$this->ficherote = $array['ficherote'];
					}
				}
				else
				{

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = File::stripExt($singleFile['name']);
					$extension = File::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/images/fotos_estaciones/'.$this->ind_climatologico . '/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!File::exists($uploadPath))
					{
						if (!File::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->ficherote .= (!empty($this->ficherote)) ? "," : "";
					$this->ficherote .= $filename;
				}
			}
		}
		else
		{
			$this->ficherote .= $array['ficherote_hidden'];
		}


		return parent::check();
	}

	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 *
	 * @return string The asset name
	 *
	 * @see Table::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return $this->typeAlias . '.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @param   Table   $table  Table name
	 * @param   integer  $id     Id
	 *
	 * @see Table::_getAssetParentId
	 *
	 * @return mixed The id on success, false on failure.
	 */
	protected function _getAssetParentId($table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = Table::getInstance('Asset');

		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// The item has the component as asset-parent
		$assetParent->loadByName('com_mantenimiento');

		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

	//XXX_CUSTOM_TABLE_FUNCTION


    /**
     * Delete a record by id
     *
     * @param   mixed  $pk  Primary key value to delete. Optional
     *
     * @return bool
     */
    public function delete($pk = null)
    {
        $this->load($pk);
        $result = parent::delete($pk);

		/*if ($result)
		{
			jimport('joomla.filesystem.file');

			$checkImageVariableType = gettype($this->ficherote);

			switch ($checkImageVariableType)
			{
			case 'string':
				File::delete(JPATH_ROOT . '/images/' . $this->ficherote);
			break;
			default:
			foreach ($this->ficherote as $ficheroteFile)
			{
				File::delete(JPATH_ROOT . '/images/' . $ficheroteFile);
			}
			}
		}*/


        return $result;
    }
}
